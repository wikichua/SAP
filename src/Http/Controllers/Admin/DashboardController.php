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
    public function wiki(Request $request, $file = 'Index.md')
    {
        $md = \File::get(base_path('vendor/wikichua/sap/wiki/'.$file));
        $search = [
            '(Installation.md)',
            '(Module-Development.md)',
            '(Brand-Development.md)',
            '(Available-Components.md)',
            '(Available-Helper.md)',
        ];
        $replace = [
            '('.route('wiki.home', ['Installation.md']).')',
            '('.route('wiki.home', ['Module-Development.md']).')',
            '('.route('wiki.home', ['Brand-Development.md']).')',
            '('.route('wiki.home', ['Available-Components.md']).')',
            '('.route('wiki.home', ['Available-Helper.md']).')',
        ];
        if (\Str::contains($md, $search)) {
            $md = str_replace($search, $replace, $md);
        }

        return view('sap::admin.dashboard.wiki', compact('md'));
    }
}
