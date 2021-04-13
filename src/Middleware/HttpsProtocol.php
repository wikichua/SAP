<?php

namespace Wikichua\SAP\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class HttpsProtocol
{
    public function handle($request, Closure $next)
    {
        if (!$request->secure() && 'production' === App::environment()) {
            return redirect()->secure($request->getRequestUri());
        }

        return $next($request);
    }
}
