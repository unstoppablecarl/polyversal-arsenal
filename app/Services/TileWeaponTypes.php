<?php

namespace App\Services;

use App\Models\TileWeaponType;
use App\Services\Support\StaticDBData;

class TileWeaponTypes extends StaticDBData
{
    protected $modelClass = TileWeaponType::class;

    public const GROUND_ID = 1;
    public const WITH_AA_ID = 2;
    public const ONLY_AA_ID = 3;

    public const GROUND = 'ground';
    public const WITH_AA = 'with_aa';
    public const ONLY_AA = 'only_aa';

    protected $data = [
        self::GROUND_ID            => [
            'id'           => self::GROUND_ID,
            'name'         => self::GROUND,
            'display_name' => 'Ground',
        ],
        self::WITH_AA_ID => [
            'id'           => self::WITH_AA_ID,
            'name'         => self::WITH_AA,
            'display_name' => 'Ground With AA',
        ],
        self::ONLY_AA_ID => [
            'id'           => self::ONLY_AA_ID,
            'name'         => self::ONLY_AA,
            'display_name' => 'Only AA',
        ],
    ];
}
