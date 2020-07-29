<?php

namespace Wikichua\SAP\View\Components;

use Illuminate\View\Component;

class InputField extends Component
{
    public $args;
    
    public function __construct()
    {
    }

    public function render()
    {
        return view('sap::components.input-field');
    }
}
