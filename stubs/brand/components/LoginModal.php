<?php

namespace Brand\{%brand_name%}\Components;

use Illuminate\View\Component;

class LoginModal extends Component
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
        return view('{%brand_string%}::components.login-modal');
    }
}
