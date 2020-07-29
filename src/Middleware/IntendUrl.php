<?php

namespace Wikichua\SAP\Middleware;

class IntendUrl
{
    public function handle($request, $next)
    {
        // set intended url for "go back" button
        session()->put('url.intended', $request->url());

        return $next($request);
    }
}
