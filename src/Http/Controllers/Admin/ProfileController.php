<?php

namespace Wikichua\SAP\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        $user->rolesShow = $user->roles->sortBy('name')->implode('name', ', ');
        $user->rolesSelected = $user->roles()->pluck('roles.id');
        return response()->json($user);
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'name' => 'required',
            'email' => 'required',
        ]);

        $user->update($request->all());

        activity('Updated Profile: ' . $user->id, $request->all(), $user);

        return response()->json([
            'id' => $user->id,
            'status' => 'success',
            'message' => 'Profile Updated.',
            'reloadPage' => true,
        ]);
    }

    public function updatePassword(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required',
        ]);

        $request->merge([
            'password' => bcrypt($request->get('password')),
        ]);

        $user->update($request->all());

        activity('Update Profile Password: ' . $user->id, $request->all(), $user);

        return response()->json([
            'id' => $user->id,
            'status' => 'success',
            'message' => 'Profile Password Updated.',
            'reloadPage' => true,
        ]);
    }
}
