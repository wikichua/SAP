<?php

namespace Wikichua\SAP\Http\Controllers\Pub;

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
        $this->redirectTo = route('pub.home');
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('sap::auth.login')->with(['loginUrl' => route('pub.login')]);
    }

    protected function attemptLogin(Request $request)
    {
        return $this->guard('pub')->attempt(
            $this->credentials($request),
            $request->filled('remember')
        );
    }

    protected function credentials(Request $request)
    {
        return $request->only($this->username(), 'password') + ['type' => 'User'];
    }

    public function logout(Request $request)
    {
        $this->guard('pub')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new Response('', 204)
            : redirect(route('pub.index'));
    }
    public function redirectToProvider(string $provider)
    {
        try {
            $scopes = config("services.$provider.scopes") ?? [];
            if (count($scopes) === 0) {
                return Socialite::driver($provider)->redirect();
            } else {
                return Socialite::driver($provider)->scopes($scopes)->redirect();
            }
        } catch (\Exception $e) {
            abort(404);
        }
    }
    public function handleProviderCallback(string $provider)
    {
        dd('here');
        try {
            $data = Socialite::driver($provider)->user();
            return $this->handleSocialUser($provider, $data);
        } catch (\Exception $e) {
            return redirect(route('pub.login'))->withErrors(['authentication_deny' => 'Login with '.ucfirst($provider).' failed. Please try again.']);
        }
        // $user->token;
    }
    public function handleSocialUser(string $provider, object $data)
    {
        $user = User::where([
            "social->{$provider}->id" => $data->id,
        ])->first();
        if (!$user) {
            $user = User::where([
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
            $user = new User;
            $user->name = $data->getName();
            $user->email = $data->getEmail();
            $user->avatar = $data->getAvatar();
            $user->type = 'User';
            $user->social = [
                $provider => [
                    'id' => $data->id,
                    'token' => $data->token,
                ],
            ];
            // Check support verify or not
            if ($user instanceof MustVerifyEmail) {
                $user->markEmailAsVerified();
            }
            $user->save();
            return $this->socialLogin($user);
        } catch (Exception $e) {
            return redirect(route('pub.login'))->withErrors(['authentication_deny' => 'Login with '.ucfirst($provider).' failed. Please try again.']);
        }
    }
    public function socialLogin(User $user)
    {
        auth('pub')->loginUsingId($user->id);
        return redirect($this->redirectTo);
    }
}
