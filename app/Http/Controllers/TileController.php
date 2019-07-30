<?php

namespace App\Http\Controllers;

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

    public function grid(Request $request, TileListService $service)
    {
        $query   = $service->getQuery(Auth::user()->id);
        $perPage = $request->input('per_page', 10);

        $sortField     = $request->input('sort_field', 'id');
        $sortDirection = $request->input('sort_dir', 'asc');

        $sortFieldMap = [
            'targeting'  => 'targeting_id',
            'assault'    => 'assault_id',
            'tech_level' => 'tech_level_id',
            'mobility'   => 'mobility_id',
            'tile_class' => 'tile_class_id',
            'tile_type'  => 'tile_type_id',
        ];

        $sortField = array_get($sortFieldMap, $sortField, $sortField);

        $query->orderBy($sortField, $sortDirection);

        return TileGridResource::collection($query->paginate($perPage));
    }

    public function create()
    {
        return view('tiles.create');
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
        return view('tiles.edit');
    }

    public function update(TileSaveRequest $request, TileService $service, CostService $costService, Tile $tile)
    {

        $service->update($tile, $request->all());

        $diff = $costService->getCostDiff($tile, $request->input('costs'));

dd($diff);
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
