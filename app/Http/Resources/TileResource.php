<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class TileResource extends Resource
{

    protected $costDiff;

    public function setCostDiff($costDiff)
    {
        $this->costDiff = $costDiff;
    }

    public function toArray($request)
    {
        $costDiff = $this->costDiff;
        if (!$costDiff) {
            $costDiff = null;
        }
        return [
            'tile'         => [
                'id'                     => $this->id,
                'name'                   => $this->name,
                'user_id'                => $this->user_id,
                'tile_type_id'           => $this->chassis->tile_type_id,
                'tile_class_id'          => $this->chassis->tile_class_id,
                'tech_level_id'          => $this->chassis->tech_level_id,
                'mobility_id'            => $this->chassis->mobility_id,
                'targeting_id'           => $this->targeting_id,
                'assault_id'             => $this->assault_id,
                'anti_missile_system_id' => $this->anti_missile_system_id,
                'armor'                  => $this->armor,
                'stealth'                => $this->stealth,
                'cached_cost'            => $this->cached_cost,
                'image_url'              => $this->front_image_file,
            ],
            'ability_ids'  => $this->abilities()->pluck('abilities.id'),
            'tile_weapons' => TileWeaponResource::collection($this->tileWeapons),
            'cost_diff'    => $costDiff,
        ];
    }
}
