<?php

namespace Brand\{%brand_name%}\Components;

use Illuminate\View\Component;

class NavbarTop extends Component
{
    public $group_slug;
    public $brand;
    public function __construct($groupSlug)
    {
        $this->brand = '{%brand_string%}';
        $this->group_slug = $groupSlug;
    }
    public function render()
    {
        $brand_id = brand($this->brand)->id;
        $brand_name = strtolower(brand($this->brand)->name);
        $navs = app(config('sap.models.nav'))->query()
            ->where('status', 'A')
            ->where('locale', app()->getLocale())
            ->where('brand_id', $brand_id)
            ->where('group_slug', $this->group_slug)
            ->orderBy('seq')
            ->get();
        return view('{%brand_string%}::components.navbar-top', compact('navs','brand_name'));
    }
}
