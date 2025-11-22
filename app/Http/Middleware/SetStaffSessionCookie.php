<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class SetStaffSessionCookie
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->is('admin/*')) {
            Config::set('session.cookie', env('ADMIN_SESSION_COOKIE', 'admin_session'));
        }

        if ($request->is('barista/*')) {
            Config::set('session.cookie', env('BARISTA_SESSION_COOKIE', 'barista_session'));
        }

        return $next($request);
    }
}
