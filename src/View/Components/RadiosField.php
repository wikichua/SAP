<?php

namespace Wikichua\SAP\View\Components;

use Illuminate\View\Component;

class RadiosField extends Component
{
    public function __construct()
    {
    }

    public function render()
    {
        return view('sap::components.radios-field');
    }
}
