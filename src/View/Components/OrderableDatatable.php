<?php

namespace Wikichua\SAP\View\Components;

use Illuminate\View\Component;

class OrderableDatatable extends Component
{
    public function __construct()
    {
    }

    public function render()
    {
        return view('sap::components.orderable-datatable');
    }
}
