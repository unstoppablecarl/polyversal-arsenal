<?php

namespace App\Http\Resources;

use App\Services\Tile\TileImage;
use Illuminate\Http\Resources\Json\Resource;

class TileResource extends Resource
{

    protected $costDiff;
    /** @var TileImage */
    protected $tileImage;

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

        $this->tileImage = app(TileImage::class);

        return [
            'tile' => [
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
                'flavor_text'            => $this->flavor_text,
                'front_source_image_url' => $this->url($this->front_source_image),
                'back_source_image_url'  => $this->url($this->back_source_image),

                'front_image_url' => $this->url($this->front_image),
                'front_thumb_url' => $this->url($this->front_thumb),
                'back_image_url'  => $this->url($this->back_image),
                'back_thumb_url'  => $this->url($this->back_thumb),
                'front_svg_url'   => $this->url($this->front_svg),
                'back_svg_url'    => $this->url($this->back_svg),
            ],

            'ability_ids'  => $this->abilities()->pluck('abilities.id'),
            'tile_weapons' => TileWeaponResource::collection($this->tileWeapons),
            'cost_diff'    => $costDiff,
        ];
    }

    protected function url($file)
    {
        if (!$file) {
            return;
        }

        return $this->tileImage->url($file);
    }
}
