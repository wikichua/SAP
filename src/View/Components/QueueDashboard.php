<?php

namespace Wikichua\SAP\View\Components;

use Illuminate\Support\Facades\Queue;
use Illuminate\View\Component;

class QueueDashboard extends Component
{
    public function __construct()
    {
    }

    public function render()
    {
        $queues = [];
        $msg = 'For now only Redis supportted on this dashboard';
        if (config('queue.default') == 'redis') {
            $msg = '';
            $keys = queue_keys();
            foreach ($keys as $key) {
                $queues[$key] = Queue::size($key);
            }
        }
        return view('sap::components.queue-dashboard')->with(compact('queues', 'msg'));
    }
}
