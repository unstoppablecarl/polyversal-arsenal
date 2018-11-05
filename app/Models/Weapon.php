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
        'is_indirect',
        'has_warheads'
    ];

    public function scopeWherePublic($query)
    {
        return $query->whereNull('user_id');
    }
}
