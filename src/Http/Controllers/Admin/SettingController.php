<?php

namespace Wikichua\SAP\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index(Request $request)
    {
        $fields = collect($request->get('fields'))->where('filterable', true)->pluck('key')->all();
        $filter = $request->get('filter', '');
        $sortBy = $request->get('sortBy', '');
        $sortDesc = $request->get('sortDesc', '');
        $settings = app(config('vam.models.setting'))->query()->filter($filter, $fields)->sorting($sortBy, $sortDesc)->paginate($request->get('size', 20));
        return response()->json($settings);
    }

    public function store(Request $request)
    {
        $request->validate([
            'key' => 'required',
        ]);

        if ($request->get('multipleTypes', false) == true) {
            $request->merge(['value' => array_combine($request->get('indexes', []), $request->get('values', []))]);
        }

        $setting = app(config('vam.models.setting'))->create($request->all());

        activity('Created Setting: ' . $setting->id, $request->all(), $setting);

        return response()->json([
            'id' => $setting->id,
            'status' => 'success',
            'message' => 'Setting created.',
            'redirectTo' => '/setting/' . $setting->id . '/show',
        ]);
    }

    public function show($id)
    {
        $setting = app(config('vam.models.setting'))->query()->findOrFail($id);
        return response()->json($setting);
    }

    public function update(Request $request, $id)
    {
        $setting = app(config('vam.models.setting'))->query()->findOrFail($id);
        $request->validate([
            'key' => 'required',
        ]);

        if ($request->get('multipleTypes', false) == true) {
            $request->merge(['value' => array_combine($request->get('indexes', []), $request->get('values', []))]);
        }

        $setting->update($request->all());

        activity('Updated Setting: ' . $setting->id, $request->all(), $setting);

        return response()->json([
            'id' => $setting->id,
            'status' => 'success',
            'message' => 'Setting Updated.',
            'redirectTo' => '/setting/' . $setting->id . '/show',
        ]);
    }

    public function destroy($id)
    {
        $setting = app(config('vam.models.setting'))->query()->findOrFail($id);
        $setting->delete();

        activity('Deleted Setting: ' . $setting->id, [], $setting);

        return response()->json([
            'id' => $setting->id,
            'status' => 'success',
            'message' => 'Setting deleted.',
        ]);
    }
}
