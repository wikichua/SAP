<?php

namespace Brand\{%brand_name%}\Components;

use Illuminate\Support\Facades\Session;
use Illuminate\View\Component;

class PusherScript extends Component
{
    public function __construct()
    {
        $this->brand = '{%brand_string%}';
    }
    public function render()
    {
        $driver = config('sap.custom_broadcast_driver');
        if ($driver == '') {
            return '';
        }
        $config = config('broadcasting.connections.'.$driver);
        $app_key = $config['key'];
        $cluster = isset($config['options']['cluster'])? $config['options']['cluster']:'';
        $app_logo = asset('sap/logo.png');
        $app_title = config('app.name').' Web Notification';
        $channel = sha1($this->brand);
        $general_event = sha1('general-'.app()->getLocale());
        return view('{%brand_string%}::components.pusher-script')->with(compact(
            'cluster',
            'app_key',
            'app_logo',
            'app_title',
            'channel',
            'general_event',
            'driver'
        ));
    }
}
