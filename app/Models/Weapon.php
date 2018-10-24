<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Weapon extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'slug',
        'name',
        'ap',
        'at',
        'aa',
        'damage',
        'is_ama',
        'is_indirect',
    ];

    public function scopeWherePublic($query)
    {
        return $query->whereNull('user_id');
    }
}
