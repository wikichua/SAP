<?php

namespace Brand\{%brand_name%}\Components;

use Illuminate\Support\Facades\Session;
use Illuminate\View\Component;

class PusherScript extends Component
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
        $config = config('broadcasting.connections.pusher');
        if ($config['key'] == '') {
            return '';
        }
        $my_encrypted_id = sha1(auth()->check()? auth()->id():0);
        $cluster = $config['options']['cluster'];
        $app_key = $config['key'];
        $app_logo = asset('sap/logo.png');
        $app_title = config('app.name').' Web Notification';
        $channel = sha1(config('app.name'));
        $general_event = sha1('general');
        return view('{%brand_string%}::components.pusher-script')->with(compact(
            'my_encrypted_id',
            'cluster',
            'app_key',
            'app_logo',
            'app_title',
            'channel',
            'general_event'
        ));
    }
}
