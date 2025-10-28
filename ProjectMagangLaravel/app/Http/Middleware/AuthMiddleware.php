<?php

namespace App\Http\Middleware;

use Closure;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
         if (!session('is_logged_in')) {
        return redirect('/login')->with('alert-warning', 'Silakan login terlebih dahulu');
    }

    return $next($request);
    }
}
