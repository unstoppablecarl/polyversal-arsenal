<?php

namespace App\Http\Controllers;

use App\Http\Requests\TileCreateRequest;
use App\Models\Tile;
use App\Services\TileService;
use Illuminate\Http\Request;

class TileController extends Controller
{
    public function index(Request $request)
    {
        $tiles = $request->user()->tiles();
        return view('tiles.index')
            ->with([
                'tiles' => $tiles,
            ]);
    }

    public function create()
    {
        return view('tiles.create');
    }

    public function store(TileCreateRequest $request, TileService $service)
    {
        $tile = $service->create($request);

        return $this->redirectAction('edit', $tile);
    }

    public function edit(Tile $tile)
    {
        $abilityIds = $tile->abilities();
        return view('tiles.edit')
            ->with([
                'tile' => $tile,
            ]);
    }

    public function update(Request $request, Tile $tile)
    {
        $tile->update($request->all());

        return $this->redirectAction('edit', $tile);
    }

    public function show(Tile $tile)
    {
        return view('tiles.show')
            ->with([
                'tile' => $tile,
            ]);
    }

    public function delete(Tile $tile)
    {
        return view('tiles.delete')
            ->with([
                'tile' => $tile,
            ]);
    }

    public function destroy(Tile $tile)
    {
        $tile->delete();
        return $this->redirectAction('index');
    }
}
