<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Arc extends Model
{
    protected $fillable = [
        'direction',
        'size',
    ];

    public $timestamps = false;

    public function tileWeapon()
    {
        return $this->belongsTo(TileWeapon::class);
    }
}
