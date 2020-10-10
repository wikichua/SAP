<?php

namespace Brand\{%brand_name%}\Components;

use Illuminate\View\Component;

class NavbarTopLogin extends Component
{
    public $brand;
    public $hasLogin;
    public function __construct()
    {
        $this->brand = '{%brand_string%}';
        $this->hasLogin = auth('brand_web')->check();
    }
    public function render()
    {
        return view('{%brand_string%}::components.navbar-top-login');
    }
}
