<?php

namespace Wikichua\SAP\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $fields = collect($request->get('fields'))->where('filterable', true)->pluck('key')->all();
        $filter = $request->get('filter', '');
        $sortBy = $request->get('sortBy', '');
        $sortDesc = $request->get('sortDesc', '');
        $permissions = app(config('vam.models.permission'))->query()->filter($filter, $fields)->sorting($sortBy, $sortDesc)->paginate($request->get('size', 20));
        return response()->json($permissions);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'group' => 'required',
        ]);

        $permission = app(config('vam.models.permission'))->create($request->all());

        activity('Created Permission: ' . $permission->id, $request->all(), $permission);

        return response()->json([
            'id' => $permission->id,
            'status' => 'success',
            'message' => 'Permission created.',
            'redirectTo' => '/permission/' . $permission->id . '/show',
        ]);
    }

    public function show($id)
    {
        $permission = app(config('vam.models.permission'))->query()->findOrFail($id);
        return response()->json($permission);
    }

    public function update(Request $request, $id)
    {
        $permission = app(config('vam.models.permission'))->query()->findOrFail($id);
        $request->validate([
            'name' => 'required',
            'group' => 'required',
        ]);

        $permission->update($request->all());

        activity('Updated Permission: ' . $permission->id, $request->all(), $permission);

        return response()->json([
            'id' => $permission->id,
            'status' => 'success',
            'message' => 'Permission Updated.',
            'redirectTo' => '/permission/' . $permission->id . '/show',
        ]);
    }

    public function destroy($id)
    {
        $permission = app(config('vam.models.permission'))->query()->findOrFail($id);
        $permission->delete();

        activity('Deleted Permission: ' . $permission->id, [], $permission);

        return response()->json([
            'id' => $permission->id,
            'status' => 'success',
            'message' => 'Permission deleted.',
        ]);
    }

    public function checkboxes(Request $request)
    {
        $bootstrapVueCheckboxes = [];
        foreach (app(config('vam.models.permission'))->orderBy('group')->orderBy('name')->get() as $permission) {
            $bootstrapVueCheckboxes[$permission->group][] = [
                'value' => $permission->id,
                'text' => $permission->name,
            ];
        }
        return response()->json($bootstrapVueCheckboxes);
    }
}
