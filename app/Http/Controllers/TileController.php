<?php

namespace App\Http\Controllers;

use App\Http\Requests\TileSaveRequest;
use App\Http\Resources\TileResource;
use App\Models\Tile;
use App\Services\CostService;
use App\Services\TileListService;
use App\Services\TileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TileController extends Controller
{
    public function index(Request $request, TileListService $service)
    {
        $tiles = $service->get(Auth::user()->id);

        $tiles->each(function ($row) {
            $model      = new Tile((array)$row);
            $model->id  = $row->id;
            $row->model = $model;
        });

        return view('tiles.index')
            ->with([
                'items' => $tiles,
            ]);
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
        return view('tiles.app');
        // ->with([
        //     'item' => $tile,
        // ]);
    }

    public function update(TileSaveRequest $request, TileService $service, Tile $tile)
    {
        $service->update($tile, $request->all());
        if ($request->wantsJson()) {
            return new TileResource($tile);
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
        return view('tiles.delete')
            ->with([
                'item' => $tile,
            ]);
    }

    public function destroy(Request $request, Tile $tile)
    {
        $tile->delete();
        if ($request->wantsJson()) {
            return;
        }
        return $this->redirectAction('index');
    }
}
