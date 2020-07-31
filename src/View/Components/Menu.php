<?php

namespace Wikichua\SAP\View\Components;

use Illuminate\View\Component;

class Menu extends Component
{
	public $menu, $activePatterns, $groupActive;

    public function __construct($menu = '', $activePatterns = [])
    {
        $this->menu = $menu;
        $this->activePatterns = $activePatterns;
    }

    public function render()
    {
    	if ($this->menu == '') {
	        return view('sap::components.menu');
    	}
    	$group = '/'.implode('|', $this->activePatterns).'/';
    	$this->groupActive = preg_match($group, request()->route()->getName())? true:false;

    	return view('sap::components.menus.'.$this->menu);
    }
}
