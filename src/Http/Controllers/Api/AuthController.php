<?php

namespace Wikichua\SAP\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Sanctum\Sanctum;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'email|required',
                'password' => 'required',
            ]);
            $credentials = $request->only(['email', 'password']);
            if (!\Auth::once($credentials)) {
                return response()->json([
                    'status_code' => 500,
                    'message' => 'Unauthorized'
                ]);
            }
            $user = app(config('sap.models.user'))->query()->where('email', $request->email)->first();
            if (!\Hash::check($request->password, $user->password, [])) {
                throw new \Exception('Error in Login');
            }
            $permissions = $user->roles->contains('admin', true)? ['*']:$user->flatPermissions()->toArray();
            // $user->tokens()->delete();
            $tokenResult = $user->createToken('authToken', $permissions)->plainTextToken;
            $tokenResult = explode('|', $tokenResult);
            $personalAccessTokenModel = app(Sanctum::$personalAccessTokenModel)->query()
                ->find($tokenResult[0]);
            $personalAccessTokenModel->plain_text_token = $tokenResult[1];
            $personalAccessTokenModel->save();
            return response()->json([
                'status_code' => 200,
                'access_token' => $tokenResult[1],
                'permissions' => $permissions,
                'token_type' => 'Bearer',
            ]);
        } catch (Exception $error) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Error in Login',
                'error' => $error,
            ]);
        }
    }
}
