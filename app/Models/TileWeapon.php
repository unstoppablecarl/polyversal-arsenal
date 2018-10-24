<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TileWeapon extends Model
{
    protected $fillable = [
        'tile_id',
        'weapon_id',
        'arc_id',
        'tile_weapon_type_id',
        'quantity',
        'display_order',
    ];

    public function tile()
    {
        return $this->belongsTo(Tile::class);
    }

    public function weapon()
    {
        return $this->belongsTo(Weapon::class);
    }

    public function arc()
    {
        return $this->belongsTo(Arc::class);
    }
}
