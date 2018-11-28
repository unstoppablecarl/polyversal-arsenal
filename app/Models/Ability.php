<?php

namespace App\Models;

use App\Services\TileTypes;
use Illuminate\Database\Eloquent\Model;

class Ability extends Model
{
    protected $fillable = [
        'name',
        'display_name',
        'icon',

        'cost_static',

        'cost_infantry',
        'cost_cavalry',

        'cost_vehicle_class_1',
        'cost_vehicle_class_2',
        'cost_vehicle_class_3',
        'cost_vehicle_class_4',
        'cost_vehicle_class_5',

        'infantry_valid',
        'cavalry_valid',
        'vehicle_valid',

        'is_defensive',
        'display_order',
        'warhead_cost_multiplier',
    ];

    public $timestamps = false;

    public function isValidTileType($tileTypeId)
    {
        $map = [
            TileTypes::INFANTRY_ID => 'infantry_valid',
            TileTypes::CAVALRY_ID  => 'cavalry_valid',
            TileTypes::VEHICLE_ID  => 'vehicle_valid',
        ];

        $validTypeAttribute = $map[$tileTypeId];

        return (bool)$this[$validTypeAttribute];
    }
}
