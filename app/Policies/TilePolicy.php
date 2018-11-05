<?php

namespace App\Policies;

use App\Models\Tile;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TilePolicy
{
    use HandlesAuthorization;

    public function view(User $user, Tile $tile = null)
    {
        return true;
    }

    public function create(User $user)
    {
        return true;
    }

    public function update(User $user, Tile $tile)
    {
        return $tile->user_id == $user->id;
    }

    public function delete(User $user, Tile $tile)
    {
        return $tile->user_id == $user->id;
    }

}
