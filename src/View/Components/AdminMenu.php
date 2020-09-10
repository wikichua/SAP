<?php

namespace Wikichua\SAP\View\Components;

use Illuminate\View\Component;

class AdminMenu extends Component
{
    public $menu;
    public $activePatterns;
    public $groupActive;

    public function __construct($menu = '', $activePatterns = [])
    {
        $this->menu = $menu;
        $this->activePatterns = $activePatterns;
    }

    public function render()
    {
        return view('sap::components.admin-menu');
    }
}
