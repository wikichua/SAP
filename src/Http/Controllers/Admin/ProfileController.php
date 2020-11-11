<?php

namespace Wikichua\SAP\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $model = auth()->user();
        $last_activity = $model->activitylogs()->first();
        $model->last_activity = [
            'datetime' => $last_activity->created_at,
            'message' => $last_activity->message,
            'iplocation' => $last_activity->iplocation,
        ];
        return view('sap::admin.profile.show', compact('model'));
    }

    public function edit(Request $request)
    {
        $model = auth()->user();
        return view('sap::admin.profile.edit', compact('model'));
    }

    public function update(Request $request)
    {
        $model = auth()->user();

        $request->validate([
            'name' => 'required',
            'email' => 'required',
        ]);

        $request->merge([
            'updated_by' => auth()->id(),
        ]);

        $model->update($request->all());

        activity('Updated User: ' . $model->id, $request->all(), $model);

        return response()->json([
            'status' => 'success',
            'flash' => 'Profile Updated.',
            'reload' => false,
            'relist' => false,
            'redirect' => route('profile.edit', [$model->id]),
        ]);
    }

    public function editPassword(Request $request)
    {
        $model = auth()->user();
        return view('sap::admin.profile.editPassword', compact('model'));
    }

    public function updatePassword(Request $request)
    {
        $model = auth()->user();
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required',
        ]);

        $request->merge([
            'password' => bcrypt($request->get('password')),
            'updated_by' => auth()->id(),
        ]);

        $model->update($request->all());

        activity('Update Profile Password: ' . $model->id, $request->all(), $model);

        return response()->json([
            'status' => 'success',
            'flash' => 'Password Updated.',
            'reload' => true,
            'relist' => false,
            'redirect' => false,
        ]);
    }
}
