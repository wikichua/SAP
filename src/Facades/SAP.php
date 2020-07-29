<?php

namespace Wikichua\SAP\Facades;

use Illuminate\Support\Facades\Facade;

class SAP extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'sap';
    }
}
