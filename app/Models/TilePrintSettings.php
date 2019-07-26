<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TilePrintSettings extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'tile_id',

        'front_is_printer_friendly',
        'front_invert_title',
        'front_cut_line_color',
        'front_invert_abilities',

        'back_is_printer_friendly',
        'back_invert_title',
        'back_cut_line_color',
        'back_invert_bottom_headings',
        'back_invert_flavor_text',
    ];

    protected $casts = [
        'front_is_printer_friendly' => 'bool',
        'front_invert_title' => 'bool',
        'front_invert_abilities' => 'bool',

        'back_is_printer_friendly' => 'bool',
        'back_invert_title' => 'bool',
        'back_invert_bottom_headings' => 'bool',
        'back_invert_flavor_text' => 'bool',
    ];

    public function tile()
    {
        return $this->belongsTo(Tile::class);
    }
}
