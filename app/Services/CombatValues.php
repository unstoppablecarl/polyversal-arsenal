<?php

namespace App\Services;

use App\Models\CombatValue;
use App\Services\Support\StaticDBData;

class CombatValues extends StaticDBData
{
    protected $modelClass = CombatValue::class;

    public const D4_ID = 1;
    public const D6_ID = 2;
    public const D8_ID = 3;
    public const D10_ID = 4;
    public const D12_ID = 5;
    public const NONE_ID = 6;

    public const D4 = 'd4';
    public const D6 = 'd6';
    public const D8 = 'd8';
    public const D10 = 'd10';
    public const D12 = 'd12';
    public const NONE = 'none';

    protected $data = [
        self::D4_ID  => [
            'id'           => self::D4_ID,
            'name'         => self::D4,
            'display_name' => 'D4',
        ],
        self::D6_ID  => [
            'id'           => self::D6_ID,
            'name'         => self::D6,
            'display_name' => 'D6',
        ],
        self::D8_ID  => [
            'id'           => self::D8_ID,
            'name'         => self::D8,
            'display_name' => 'D8',
        ],
        self::D10_ID => [
            'id'           => self::D10_ID,
            'name'         => self::D10,
            'display_name' => 'D10',
        ],
        self::D12_ID => [
            'id'           => self::D12_ID,
            'name'         => self::D12,
            'display_name' => 'D12',
        ],
        self::NONE_ID => [
            'id'           => self::NONE_ID,
            'name'         => self::NONE,
            'display_name' => 'none',
        ],
    ];
}
