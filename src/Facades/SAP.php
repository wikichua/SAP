<?php

namespace Wikichua\SAP\Facades;

use Illuminate\Support\Facades\Facade;

class SAP extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'sap';
    }
}
