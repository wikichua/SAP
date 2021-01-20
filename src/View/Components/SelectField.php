<?php

namespace Wikichua\SAP\View\Components;

use Illuminate\View\Component;

class SelectField extends Component
{
    public function __construct()
    {
    }

    public function render()
    {
        return view('sap::components.select-field');
    }
}
