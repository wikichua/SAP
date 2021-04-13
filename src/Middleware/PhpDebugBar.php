<?php

namespace Wikichua\SAP\Middleware;

use Closure;

class PhpDebugBar
{
    public function handle($request, Closure $next)
    {
        if (config('app.debug') || (auth()->user() && in_array(auth()->id(), [1]))) {
            \Debugbar::enable();
        } else {
            \Debugbar::disable();
        }

        return $next($request);
    }
}
