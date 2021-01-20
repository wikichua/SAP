<?php

namespace Brand\{%brand_name%}\Components;

use Illuminate\Support\Facades\Session;
use Illuminate\View\Component;

class Alert extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return Session::has('alert.config')? view('{%brand_string%}::components.alert'):'';
    }
}
