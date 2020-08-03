<?php

namespace Wikichua\SAP\View\Components;

use Illuminate\View\Component;

class FileField extends Component
{
    public function __construct()
    {
    }

    public function render()
    {
        return view('sap::components.file-field');
    }
}
