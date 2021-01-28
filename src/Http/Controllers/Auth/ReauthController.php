<?php

namespace Wikichua\SAP\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReauthController extends Controller
{
    public function reauth()
    {
        return view('sap::auth.passwords.reauth');
    }

    public function processReauth(Request $request)
    {
        $request->validate([
            "password" => function ($attribute, $value, $fail) use ($request) {
                if (!\Hash::check($request->get('password'), $request->user()->password)) {
                    $fail('The '.$attribute.' is invalid.');
                }
            },
        ]);
        session()->put(str_slug(strtolower(config('app.name'))).'.reauth.last_auth', strtotime('now'));
        $url = session()->get(str_slug(strtolower(config('app.name'))).'.reauth.requested_url', '/');
        return redirect()->to($url);
    }
}
