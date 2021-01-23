<?php

namespace Wikichua\SAP\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        return view('sap::admin.dashboard.index');
    }
    public function chatify(Request $request)
    {
        return view('sap::admin.dashboard.chatify');
    }
    public function lfm(Request $request)
    {
        return view('sap::admin.dashboard.lfm');
    }
    public function seo(Request $request)
    {
        return view('sap::admin.dashboard.seo');
    }
    public function wiki(Request $request, $file = 'wiki.md')
    {
        $md = \File::get(base_path('vendor/wikichua/sap/wiki/'.$file));
        return view('sap::admin.dashboard.wiki', compact('md'));
    }
}
