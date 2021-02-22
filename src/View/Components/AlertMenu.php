<?php

namespace Wikichua\SAP\View\Components;

use Illuminate\View\Component;

class AlertMenu extends Component
{
    public function __construct()
    {
    }

    public function render()
    {
        $alerts = app(config('sap.models.alert'))->query()
                ->checkBrand()->orderBy('created_at', 'desc')->where(function ($query) {
                    return $query->where('receiver_id', 0)->orWhere('receiver_id', auth()->id());
                })->take(25)->get();
        $unread_count = $alerts->where('status', 'u')->count();
        return view('sap::components.alert-menu')->with(compact('alerts', 'unread_count'));
    }
}
