<?php

namespace Wikichua\SAP\View\Components;

use Illuminate\View\Component;

class Form extends Component
{
    public $ajax;
    public $method;
    public $action;
    public $class;
    public $id;
    public $name;
    public $confirm;

    public function __construct(
        $ajax = false,
        $method = '',
        $action = '',
        $class = '',
        $id = '',
        $name = '',
        $confirm = ''
    ) {
        $uniqid = uniqid();
        $this->method = strtoupper($method);
        $this->ajax = $ajax === 'true'? 'data-ajax-form':'';
        $this->action = $action;
        $this->class = $class;
        $this->id = $id == ''? $uniqid: $id;
        $this->name = $name == ''? $uniqid: $name;
        $this->confirm = $confirm;
    }

    public function render()
    {
        return view('sap::components.form');
    }
}
