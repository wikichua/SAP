<?php

namespace Brand\{%brand_name%}\Middlewares;

use Closure;
use Illuminate\Http\Request;
use App\Facades\GamingPortal;

class AuthMiddleware
{
    public function handle(Request $request, Closure $next, $guard)
    {
        if ($guard == 'guest') {
            return $next($request);
        }
        if (auth('brand_web')->check()) {
            return $next($request);
        }
        return redirect()->route('{%brand_string%}.home');
    }
}
