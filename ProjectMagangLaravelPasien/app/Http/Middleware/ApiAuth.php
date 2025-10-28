<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ApiAuth
{
    public function handle($request, Closure $next)
    {
        if (!Session::get('is_logged_in')) {
            Session::flash('alert', 'Silakan login terlebih dahulu.');
            return redirect('/login');
        }

        return $next($request);
    }
}
