<?php

namespace Wikichua\SAP\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        return response()->json([
              'status_code' => 200,
              'data' => $request->user(),
        ]);
    }
    public function update(Request $request)
    {
        $model = $request->user();

        $request->validate([
            'name' => 'required',
            'email' => 'required',
        ]);

        $request->merge([
            'updated_by' => $model->id,
        ]);

        $model->update($request->all());

        activity('Updated User: ' . $model->id, $request->all(), $model);

        return response()->json([
            'status_code' => 200,
            'data' => $request->user(),
        ]);
    }
    public function updatePassword(Request $request)
    {
        $model = $request->user();
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required',
        ]);

        $request->merge([
            'password' => bcrypt($request->get('password')),
            'updated_by' => $model->id,
        ]);

        $model->update($request->all());

        activity('Update Profile Password: ' . $model->id, $request->all(), $model);

        return response()->json([
            'status_code' => 200,
            'data' => $request->user(),
        ]);
    }
}
