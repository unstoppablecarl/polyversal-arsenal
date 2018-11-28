<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class TileWeaponResource extends Resource
{
    public function toArray($request)
    {
        return [
            'id'                  => $this->id,
            'tile_id'             => $this->tile_id,
            'weapon_id'           => $this->weapon_id,
            'tile_weapon_type_id' => $this->tile_weapon_type_id,
            'arc_direction_id'    => $this->arc_direction_id,
            'arc_size_id'         => $this->arc_size_id,
            'quantity'            => $this->quantity,
            'display_order'       => $this->display_order,
        ];
    }
}
