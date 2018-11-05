<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CombatValue extends Model
{
    protected $fillable = [
        'name',
        'display_name',
    ];

    public $timestamps = false;
}
