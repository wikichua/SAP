<?php

namespace Wikichua\SAP\Repos;

use Closure;
use Illuminate\Http\Request;
use Spatie\Honeypot\SpamResponder\SpamResponder;

class HoneypotResponder implements SpamResponder
{
    public function respond(Request $request, Closure $next)
    {
        return abort(403, 'Spammer Suspected');
    }
}
