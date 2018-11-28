<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class TileWeapon extends Pivot
{
    protected $table = 'tile_weapons';

    protected $fillable = [
        'tile_id',
        'weapon_id',
        'tile_weapon_type_id',
        'arc_direction_id',
        'arc_size_id',
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

    public function arcSize()
    {
        return $this->belongsTo(ArcSize::class);
    }

    public function arcDirection()
    {
        return $this->belongsTo(ArcDirection::class);
    }

    public function tileWeaponType()
    {
        return $this->belongsTo(TileWeaponType::class);
    }
}
