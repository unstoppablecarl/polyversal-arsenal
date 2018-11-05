<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function tiles()
    {
        return $this->hasMany(Tile::class);
    }

    public function weapons()
    {
        return $this->hasMany(Weapon::class);
    }
}
