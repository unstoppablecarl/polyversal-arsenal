<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class KickBannedUsers
{
    public function handle($request, Closure $next)
    {
        $user = $request->user();

        if ($user && $user->is_banned) {
            Auth::logout();
            return redirect()->back()->withErrors('You have been banned');
        }

        return $next($request);
    }
}
