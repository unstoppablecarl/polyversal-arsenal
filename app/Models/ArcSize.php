<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArcSize extends Model
{
    protected $fillable = [
        'name',
        'display_name',
    ];

    public $timestamps = false;
}
