<?php

namespace Wikichua\SAP\Facades;

use Illuminate\Support\Facades\Facade;

class Help extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Wikichua\SAP\Repos\Help::class;
    }
}
