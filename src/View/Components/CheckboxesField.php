<?php

namespace Wikichua\SAP\View\Components;

use Illuminate\View\Component;

class CheckboxesField extends Component
{
    public function __construct()
    {
    }

    public function render()
    {
        return view('sap::components.checkboxes-field');
    }
}
