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
        $keys = queue_keys();
        $queues = [];
        foreach ($keys as $key) {
            $queues[$key] = Queue::size($key);
        }
        return view('sap::components.queue-dashboard')->with(compact('queues'));
    }
}
