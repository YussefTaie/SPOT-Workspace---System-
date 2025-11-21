<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        // تحقق إن الـ staff مسجل دخول عبر guard staff
        if (!Auth::guard('staff')->check()) {
            abort(403, 'Unauthorized.');
        }

        $user = Auth::guard('staff')->user();

        if (!isset($user->role) || $user->role !== 'admin') {
            abort(403, 'Unauthorized.');
        }

        return $next($request);
    }
}
