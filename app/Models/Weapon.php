<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Weapon extends Model
{

    protected $fillable = [
        'user_id',
        'name',
        'display_name',
        'ap',
        'at',
        'aa',
        'damage',
        'range',
        'is_infantry',
        'is_indirect',
        'has_warheads',
        'cost_d4',
        'cost_d6',
        'cost_d8',
        'cost_d10',
        'cost_d12',

    ];

    public function scopeWherePublic($query)
    {
        return $query->whereNull('user_id');
    }
}
