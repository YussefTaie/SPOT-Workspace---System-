<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsBarista
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::guard('barista')->user();
        if (!$user || $user->role !== 'barista') {
            abort(403, 'Forbidden');
        }
        return $next($request);
        

    }
}
