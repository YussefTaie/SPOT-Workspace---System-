<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsHost
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::guard('host')->user();

            if (!$user || $user->role !== 'host') {
                abort(403, 'Unauthorized');
            }


        return $next($request);
    }
}
