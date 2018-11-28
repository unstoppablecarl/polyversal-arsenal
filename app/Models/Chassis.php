<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chassis extends Model
{
    protected $fillable = [
        'app_version',
        'tile_type_id',
        'tile_class_id',
        'tech_level_id',
        'mobility_id',
        'armor',
        'evasion',
        'cost',
    ];

    public $timestamps = false;

    public function tileType()
    {
        return $this->belongsTo(TileType::class);
    }

    public function tileClass()
    {
        return $this->belongsTo(TileClass::class);
    }

    public function mobility()
    {
        return $this->belongsTo(Mobility::class);
    }

    public function techLevel()
    {
        return $this->belongsTo(TechLevel::class);
    }

    public function chassisArmorStat()
    {
        return $this->hasMany(ChassisArmorStat::class);
    }
}
