<?php

namespace Wikichua\SAP\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->redirectTo = route('admin');
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('sap::auth.login');
    }

    protected function credentials(Request $request)
    {
        return $request->only($this->username(), 'password') + ['type' => 'Admin'];
    }
}
