<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TileAbility extends Model
{
    protected $fillable = [
        'tile_id',
        'ability_id',
    ];

    public $timestamps = false;

    public function tile()
    {
        return $this->belongsTo(Tile::class);
    }

    public function ability()
    {
        return $this->belongsTo(Ability::class);
    }
}
