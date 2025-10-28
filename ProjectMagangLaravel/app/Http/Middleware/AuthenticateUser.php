<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthenticateUser
{
    public function handle($request, Closure $next)
    {
        if (!session('is_logged_in')) {
        return redirect('/login')->with('alert-warning', 'Silakan login terlebih dahulu');
        }

        return $next($request);
    }
}
