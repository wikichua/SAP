<?php

namespace Wikichua\SAP\View\Components;

use Illuminate\View\Component;

class MenuItem extends Component
{
    public $activePattern, $href, $menuActive;

    public function __construct($activePattern = '')
    {
        $this->activePattern = $activePattern;
    }

    public function render()
    {
        $this->menuActive = false;

        if ($this->activePattern != '') {
        	$this->menuActive = preg_match('/'.($this->activePattern).'/', request()->route()->getName())? true:false;
        }

    	return view('sap::components.menu-item');
    }
}
