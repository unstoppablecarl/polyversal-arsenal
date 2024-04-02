<?php

namespace App\Http\Controllers;

use App\Http\Requests\TileGridRequest;
use App\Http\Requests\TileSaveRequest;
use App\Http\Resources\TileGridResource;
use App\Http\Resources\TileResource;
use App\Models\Tile;
use App\Services\CostService;
use App\Services\TileListService;
use App\Services\TileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TileController extends Controller
{
    public function index()
    {
        return view('tiles.index');
    }

    public function grid(TileGridRequest $request, TileListService $service)
    {
        $search        = $request->search();
        $perPage       = $request->perPage();
        $sortField     = $request->sortField();
        $sortDirection = $request->sortDirection();

        $userId = Auth::user()->id;
        $query  = $service->getQuery($userId, $sortField, $sortDirection, $search);

        return TileGridResource::collection($query->paginate($perPage))
            ->additional([
                'meta' => [
                    'search'         => $search,
                    'sort_field'     => $sortField,
                    'sort_direction' => $sortDirection,
                ]
            ]);
    }

    public function create()
    {
        return view('tiles.create')->with([
            'JS_DATA' => [
                'max_image_upload_size_mb' => config('image-uploads.max_file_size_mb')
            ]
        ]);
    }

    public function store(TileSaveRequest $request, TileService $service, CostService $costService)
    {
        $tile = $service->create($request->all(), Auth::user()->id);

        $diff = $costService->getCostDiff($tile, $request->input('costs'));

        if ($request->wantsJson()) {
            $resource = new TileResource($tile);
            $resource->setCostDiff($diff);
            return $resource;
        }
        return $this->redirectAction('edit', $tile);
    }

    public function edit(Tile $tile)
    {
        return view('tiles.edit')->with([
            'JS_DATA' => [
                'max_image_upload_size_mb' => config('image-uploads.max_file_size_mb')
            ]
        ]);
    }

    public function update(TileSaveRequest $request, TileService $service, CostService $costService, Tile $tile)
    {

        $service->update($tile, $request->all());

        $diff = $costService->getCostDiff($tile, $request->input('costs'));

        if ($request->wantsJson()) {
            $resource = new TileResource($tile);
            $resource->setCostDiff($diff);
            return $resource;
        }

        return $this->redirectAction('edit', $tile);
    }

    public function show(Request $request, Tile $tile)
    {
        if ($request->wantsJson()) {
            return new TileResource($tile);
        }

        return view('tiles.view')
            ->with([
                'item' => $tile,
            ]);
    }

    public function copy(Tile $tile)
    {
        return view('tiles.copy')
            ->with([
                'item' => $tile,
            ]);
    }

    public function duplicate(TileService $service, Tile $tile, Request $request)
    {
        $newTile = $service->copy($tile, null, $request->input('name'));

        return $this->redirectAction('edit', ['tile' => $newTile]);
    }

    public function delete(Tile $tile)
    {
        $urls = $tile->imageUrls();

        return view('tiles.delete')
            ->with([
                'item' => $tile,

                'front_svg_url'   => $urls['front_svg_url'],
                'front_image_url' => $urls['front_image_url'],

                'back_svg_url'   => $urls['back_svg_url'],
                'back_image_url' => $urls['back_image_url'],
            ]);
    }

    public function destroy(Request $request, TileService $service, Tile $tile)
    {
        $service->delete($tile);
        if ($request->wantsJson()) {
            return;
        }
        return $this->redirectAction('index');
    }
}
