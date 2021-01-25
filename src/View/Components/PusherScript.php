<?php

namespace Wikichua\SAP\View\Components;

use Illuminate\View\Component;

class PusherScript extends Component
{
    public function __construct()
    {
    }

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
        return view('sap::components.pusher-script')->with(compact(
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
