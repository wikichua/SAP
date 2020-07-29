<?php

namespace Wikichua\SAP\View\Components;

use Illuminate\View\Component;

class Datatable extends Component
{
    public $html;
    public $getUrl;
    public function __construct($html = [], $getUrl)
    {
        $this->html = $html;
        $this->getUrl = $getUrl;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('sap::layouts.datatable');
    }
}
