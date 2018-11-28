<?php

namespace App\Http\Middleware;

use Closure;

class ActivateDebugBar
{
    public function handle($request, Closure $next)
    {
        $user = $request->user();

        if ($user && $user->is_admin) {
            $hardDisabled = config('debugbar.enabled') === false;
            if (!$hardDisabled) {
                app('debugbar')->enable();
            }
        }

        return $next($request);
    }
}
