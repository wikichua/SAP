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
        if (env('PUSHER_APP_KEY') == '') {
            return '';
        }
        $my_encrypted_id = sha1(auth()->check()? auth()->id():0);
        $cluster = env('PUSHER_APP_CLUSTER', 'ap1');
        $app_key = env('PUSHER_APP_KEY');
        $app_logo = asset('sap/logo.png');
        $app_title = env('APP_NAME').' Web Notification';
        $channel = sha1(env('APP_NAME'));
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
