<?php

namespace Wikichua\SAP\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Queue;

class QueueDashboard extends Component
{
    public function __construct()
    {
    }

    public function render()
    {
        $queues = [];
        $msg = 'For now only Redis supportted on this dashboard';
        if ('redis' == config('queue.default')) {
            $msg = '';
            $keys = queue_keys();
            foreach ($keys as $key) {
                $queues[$key] = Queue::size($key);
            }
        }

        return view('sap::components.queue-dashboard')->with(compact('queues', 'msg'));
    }
}
