<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tile extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'chassis_id',
        'targeting_id',
        'assault_id',
        'anti_missile_system_id',
        'armor',
        'stealth',
        'cached_cost',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function chassis()
    {
        return $this->belongsTo(Chassis::class);
    }

    public function getChassisArmorStat()
    {
        return $this->chassis
            ->chassisArmorStat()
            ->where([
                'armor' => $this->armor,
            ])
            ->first();
    }

    public function targeting()
    {
        return $this->belongsTo(CombatValue::class, 'targeting_id');
    }

    public function assault()
    {
        return $this->belongsTo(CombatValue::class, 'assault_id');
    }

    public function antiMissileSystem()
    {
        return $this->belongsTo(AntiMissileSystem::class);
    }

    public function abilities()
    {
        return $this->belongsToMany(Ability::class, 'tile_abilities');
    }

    public function weapons(): BelongsToMany
    {
        return $this->belongsToMany(Weapon::class, 'tile_weapons')
            ->using(TileWeapon::class)
            ->withPivot([
                'tile_weapon_type_id',
                'arc_direction_id',
                'arc_size_id',
                'quantity',
                'display_order',
            ]);
    }

    public function tileWeapons()
    {
        return $this->hasMany(TileWeapon::class);
    }
}
