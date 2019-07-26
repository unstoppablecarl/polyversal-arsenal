<?php

namespace App\Http\Controllers;

use App\Http\Requests\TilePrintSettingsSaveRequest;
use App\Http\Resources\TilePrintSettingsResource;
use App\Models\Tile;
use App\Services\TileService;

class TilePrintSettingsController extends Controller
{
    public function update(TilePrintSettingsSaveRequest $request, Tile $tile, TileService $service)
    {
        $input             = $request->validated();
        $tilePrintSettings = $service->updateTilePrintSettings($tile, $input);

        return new TilePrintSettingsResource($tilePrintSettings);
    }

    public function show(Tile $tile)
    {
        return new TilePrintSettingsResource($tile->tilePrintSettings);
    }
}
