<?php

namespace Brand\{%brand_name%}\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->redirectTo = redirect()->back()->getTargetUrl() ?? route('{%brand_string%}.page.home');
        $this->middleware('{%brand_string%}:guest')->except('logout');
    }

    /*public function showLoginForm()
    {
        return view('sap::auth.login')->with(['loginUrl' => route('{%brand_string%}.login')]);
    }*/

    protected function attemptLogin(Request $request)
    {
        return $this->guard('brand_web')->attempt(
            $this->credentials($request),
            $request->filled('remember')
        );
    }

    protected function credentials(Request $request)
    {
        // user model need to tie to brand_id
        return $request->only($this->username(), 'password');
    }

    public function logout(Request $request)
    {
        $this->guard('brand_web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new Response('', 204)
            : redirect($this->redirectTo);
    }
    public function redirectToProvider(string $provider)
    {
        try {
            return Socialite::driver($provider)->redirect();
        } catch (\Exception $e) {
            abort(404);
        }
    }
    public function handleProviderCallback(string $provider)
    {
        try {
            $data = Socialite::driver($provider)->user();
            return $this->handleSocialUser($provider, $data);
        } catch (\Exception $e) {
            return redirect($this->redirectTo)->withErrors(['authentication_deny' => 'Login with '.ucfirst($provider).' failed. Please try again.']);
        }
        // $user->token;
    }
    public function handleSocialUser(string $provider, object $data)
    {
        $user = app(config('main.models.user'))->query()->where([
            "social->{$provider}->id" => $data->id,
        ])->first();
        if (!$user) {
            $user = app(config('main.models.user'))->query()->where([
                'email' => $data->email,
            ])->first();
        }
        if (!$user) {
            return $this->createUserWithSocialData($provider, $data);
        }
        $social = $user->social;
        $social[$provider] = [
            'id' => $data->id,
            'token' => $data->token
        ];
        $user->social = $social;
        $user->save();
        return $this->socialLogin($user);
    }
    public function createUserWithSocialData(string $provider, object $data)
    {
        try {
            $params = [
                    'name' => $data->getName()? $data->getName():$data->getEmail(),
                    'email' => $data->getEmail(),
                    'avatar' => $data->getAvatar(),
                    'password' => bcrypt($data->getEmail()),
                    'social' => [
                        $provider => [
                            'id' => $data->id,
                            'token' => $data->token,
                        ],
                    ],
                ];
            $user = app(config('main.models.user'))->query()->create($params);
            // Check support verify or not
            if ($user instanceof MustVerifyEmail) {
                $user->markEmailAsVerified();
            }
            $user->save();
            return $this->socialLogin($user);
        } catch (Exception $e) {
            return redirect($this->redirectTo)->withErrors(['authentication_deny' => 'Login with '.ucfirst($provider).' failed. Please try again.']);
        }
    }
    public function socialLogin($user)
    {
        $this->guard('brand_web')->loginUsingId($user->id);
        return redirect($this->redirectTo);
    }
}
