<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class TilePrintSettingsResource extends Resource
{
    public function toArray($request)
    {
        return [
            'tile_id' => $this->tile_id,

            'front_is_printer_friendly' => $this->front_is_printer_friendly,
            'front_invert_title'        => $this->front_invert_title,
            'front_cut_line_color'      => $this->front_cut_line_color,
            'front_invert_abilities'    => $this->front_invert_abilities,

            'back_is_printer_friendly'    => $this->back_is_printer_friendly,
            'back_invert_title'           => $this->back_invert_title,
            'back_cut_line_color'         => $this->back_cut_line_color,
            'back_invert_bottom_headings' => $this->back_invert_bottom_headings,
            'back_invert_flavor_text'     => $this->back_invert_flavor_text,
        ];
    }
}
