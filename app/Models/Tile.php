<?php

namespace App\Models;

use App\Services\Tile\TileImage;
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
        'front_source_image',
        'back_source_image',
        'front_image',
        'front_thumb',
        'back_image',
        'back_thumb',
        'front_svg',
        'back_svg',
        'flavor_text',
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

    public function tilePrintSettings()
    {
        return $this->hasOne(TilePrintSettings::class);
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

    public function imageUrls()
    {
        $map = [
            'front_source_image_url' => $this->front_source_image,
            'back_source_image_url'  => $this->back_source_image,

            'front_image_url' => $this->front_image,
            'front_thumb_url' => $this->front_thumb,
            'back_image_url'  => $this->back_image,
            'back_thumb_url'  => $this->back_thumb,
            'front_svg_url'   => $this->front_svg,
            'back_svg_url'    => $this->back_svg,
        ];

        $tileImage = app(TileImage::class);
        $out       = [];
        foreach ($map as $key => $file) {
            if ($file) {
                $out[$key] = $tileImage->url($file);
            } else {
                $out[$key] = null;
            }
        }
        return $out;
    }
}
