<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AntiMissileSystem extends Model
{
    protected $fillable = [
        'name',
        'display_name',
    ];

    public $timestamps = false;
}
