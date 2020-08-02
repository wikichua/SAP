<?php

namespace Wikichua\SAP\View\Components;

use Illuminate\View\Component;

class DropdownMenu extends Component
{

    public function __construct()
    {
    }

    public function render()
    {
    	return view('sap::components.dropdown-menu');
    }
}
