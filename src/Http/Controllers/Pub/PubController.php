<?php

namespace Wikichua\SAP\Http\Controllers\Pub;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
