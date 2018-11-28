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

    public function setLastLogin($timestamp = null)
    {
        $this->last_login_at = $timestamp ?: $this->freshTimestamp();
        $prev                = $this->timestamps;
        $this->timestamps    = false;
        $this->save();
        $this->timestamps = $prev;
    }


    public function isBanned()
    {
        return (bool)$this->banned_at;
    }


    public function updateBannedStatus($value)
    {
        $changed = $this->isBanned() !== (bool)$value;

        if (!$changed) {
            return;
        }

        if ($value) {
            $this->setBanned();
        } else {
            $this->clearBanned();
        }
    }

    public function setBanned()
    {
        $this->banned_at = $this->freshTimestamp();
    }

    public function clearBanned()
    {
        $this->banned_at = null;
    }
}
