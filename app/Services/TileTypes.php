<?php

namespace App\Services;

use App\Models\TileWeaponType;
use App\Services\Support\StaticDBData;

class TileTypes extends StaticDBData
{
    protected $modelClass = TileType::class;

    public const INFANTRY_ID = 1;
    public const CAVALRY_ID = 2;
    public const VEHICLE_ID = 3;

    public const INFANTRY = 'infantry';
    public const CAVALRY = 'cavalry';
    public const VEHICLE = 'vehicle';

    protected $data = [
        self::INFANTRY_ID => [
            'id'           => self::INFANTRY_ID,
            'name'         => self::INFANTRY,
            'display_name' => 'Infantry',
        ],
        self::CAVALRY_ID  => [
            'id'           => self::CAVALRY_ID,
            'name'         => self::CAVALRY,
            'display_name' => 'Cavalry',
        ],
        self::VEHICLE_ID  => [
            'id'           => self::VEHICLE_ID,
            'name'         => self::VEHICLE,
            'display_name' => 'Vehicle',
        ],
    ];
}
