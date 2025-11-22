<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
{
    if (! $request->expectsJson()) {

        $path = $request->path();

        if (str_starts_with($path, 'admin')) {
            return route('staff.login');
        }

        if (str_starts_with($path, 'barista')) {
            return route('staff.login');
        }

        return route('guests.login');
    }
}

}
