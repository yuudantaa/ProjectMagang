<?php

namespace App\Http\Middleware;

use Closure;

class ApiAuth
{
    public function handle($request, Closure $next)
    {
        if (!Session::get('logged_in')) {
            Session::flash('alert', 'Silakan login terlebih dahulu.');
            return redirect('/login');
        }

        return $next($request);
    }
}
