<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tile extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'tile_type_id',
        'tile_class_id',
        'targeting_id',
        'assault_id',
        'tech_level_id',
        'mobility_id',
        'anti_missile_system_id',
        'armor',
        'stealth',
        'tile_data',
        'cached_cost',
        'app_version',
    ];

    protected $casts = [
        'tile_data' => 'json',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

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

    public function targeting()
    {
        return $this->belongsTo(CombatValue::class, 'targeting_id');
    }

    public function assault()
    {
        return $this->belongsTo(CombatValue::class, 'assault_id');
    }

    public function techLevel()
    {
        return $this->belongsTo(TechLevel::class);
    }

    public function antiMissileSystem()
    {
        return $this->belongsTo(AntiMissileSystem::class);
    }

    public function abilities()
    {
        return $this->belongsToMany(Ability::class, 'tile_abilities');
    }

    public function tileWeapons()
    {
        return $this->hasMany(TileWeaponType::class);
    }
}
