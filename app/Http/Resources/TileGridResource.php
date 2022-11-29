<?php

namespace App\Http\Resources;

use App\Models\CombatValue;
use App\Models\Tile;
use App\Services\CombatValues;
use App\Services\Tile\TileImage;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\Resource;

class TileGridResource extends Resource
{

    /** @var TileImage */
    protected $tileImage;

    public function toArray($request)
    {
        $this->tileImage = app(TileImage::class);

        $model     = new Tile((array)$this->resource);
        $model->id = $this->id;

        $combatValues = app(CombatValues::class);
        return [
            'tile_type'     => $this->tile_type,
            'tile_class'    => $this->tile_class,
            'tile_mobility' => $this->tile_mobility,
            'tech_level'    => $this->tech_level,
            'move'          => $this->move,
            'evasion'       => $this->evasion,

            'id'                     => $this->id,
            'name'                   => $this->name,
            'user_id'                => $this->user_id,
            // 'tile_type_id'           => $this->tile_type_id,
            // 'tile_class_id'          => $this->tile_class_id,
            // 'tech_level_id'          => $this->tech_level_id,
            // 'mobility_id'            => $this->mobility_id,
            'targeting_id'           => $this->targeting_id,
            'targeting'              => $this->targeting,
            'assault_id'             => $this->assault_id,
            'assault'                => $this->assault,
            'anti_missile_system_id' => $this->anti_missile_system_id,
            'anti_missile_system'    => $combatValues->idToDisplayName($this->anti_missile_system_id),
            'armor'                  => $this->armor,
            'stealth'                => $this->stealth,
            'cached_cost'            => $this->cached_cost,
            'image_url'              => $this->front_image,

            'front_image_url' => $this->url($this->front_image),
            'front_thumb_url' => $this->url($this->front_thumb),
            'front_svg_url'   => $this->url($this->front_svg),
            'back_image_url'  => $this->url($this->back_image),
            'back_thumb_url'  => $this->url($this->back_thumb),
            'back_svg_url'    => $this->url($this->back_svg),

            'updated_at'             => $this->updated_at ? Carbon::make($this->updated_at)->format('n/j/Y H:i:s') : null,
            'buttons'                => view('tiles.controls.buttons', ['item' => $model, 'size' => 'sm'])->render(),
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
