<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsBarista
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::guard('staff')->user() ?? Auth::user();

        if (! $user || strtolower($user->role ?? '') !== 'barista') {
            // ممكن ترجع 403 أو redirect لصفحة أخرى
            abort(403, 'Forbidden');
        }

        return $next($request);
    }
}
