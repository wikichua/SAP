<?php

namespace Wikichua\SAP\Http\Controllers\Pub;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PubController extends Controller
{
    public function index(Request $request)
    {
        return view('sap::pub.index');
    }

    public function home(Request $request)
    {
        return view('sap::pub.home');
    }

    public function chatify(Request $request)
    {
        return view('sap::pub.chatify');
    }
}
