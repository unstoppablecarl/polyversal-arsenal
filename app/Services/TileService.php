<?php

namespace App\Services;

use App\Http\Requests\TileCreateRequest;
use App\Http\Requests\TileUpdateRequest;
use App\Models\Tile;
use App\Models\TileWeapon;

class TileService
{
    public function create(TileCreateRequest $request): Tile
    {
        $abilityIds = $request->input('ability_ids', []);

        /** @var Tile $tile */
        $tile = Tile::query()->create($request->all());

        $tile->abilities()->sync($abilityIds);

        $tileWeapons        = $request->input('tile_weapons', []);
        $tileWeapons        = collect($tileWeapons)->keyBy('id');
        $currentTileWeapons = $tile->tileWeapons->keyBy('id');

        $tileWeapons->each(function (array $tileWeaponRow) use ($currentTileWeapons) {
            $id = $tileWeaponRow['id'];
            if (starts_with($id, 'new-')) {
                TileWeapon::query()->create($tileWeaponRow);
            } else {
                $current = $currentTileWeapons->get($id);
                $current->fill($tileWeaponRow);
                $current->save();
            }
        });


        return $tile;
    }

    public function update(TileUpdateRequest $request, Tile $tile): Tile
    {
        $abilityIds = [];

        $tile->update($request->all());


        return $tile;
    }
}
