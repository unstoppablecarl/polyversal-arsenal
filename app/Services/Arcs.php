<?php

namespace App\Services\Answer;

use App\Models\Arc;
use App\Services\Support\StaticDBData;

class Arcs extends StaticDBData
{
    public const UP_90 = 1;
    public const UP_180 = 2;
    public const UP_270 = 3;

    public const LEFT_90 = 4;
    public const LEFT_180 = 5;
    public const LEFT_270 = 6;

    public const RIGHT_90 = 7;
    public const RIGHT_180 = 8;
    public const RIGHT_270 = 9;

    public const DOWN_90 = 10;
    public const DOWN_180 = 11;
    public const DOWN_270 = 12;

    public const X_360 = 13;

    public function getId($direction, $size)
    {
        $key = $direction . '_' . $size;
        return constant(Arcs::class . '::' . $key);
    }
}
