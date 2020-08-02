<?php

namespace Wikichua\SAP\View\Components;

use Illuminate\View\Component;

class DateField extends Component
{
    public function __construct()
    {
    }

    public function render()
    {
        return view('sap::components.date-field');
    }
}
