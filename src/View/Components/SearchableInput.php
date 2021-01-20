<?php

namespace Wikichua\SAP\View\Components;

use Illuminate\View\Component;

class SearchableInput extends Component
{
    public function __construct()
    {
    }

    public function render()
    {
        return view('sap::components.searchable-input');
    }
}
