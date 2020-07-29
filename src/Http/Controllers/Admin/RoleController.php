<?php

namespace Wikichua\SAP\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $fields = collect($request->get('fields'))->where('filterable', true)->pluck('key')->all();
        $filter = $request->get('filter', '');
        $sortBy = $request->get('sortBy', '');
        $sortDesc = $request->get('sortDesc', '');
        $roles = app(config('vam.models.role'))->query()->filter($filter, $fields)->sorting($sortBy, $sortDesc)->paginate($request->get('size', 20));
        return response()->json($roles);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $role = app(config('vam.models.role'))->create($request->all());
        $role->permissions()->sync($request->get('permissions'));

        activity('Created Role: ' . $role->id, $request->all(), $role);

        return response()->json([
            'id' => $role->id,
            'status' => 'success',
            'message' => 'Role created.',
            'redirectTo' => '/role/' . $role->id . '/show',
        ]);
    }

    public function show($id)
    {
        $role = app(config('vam.models.role'))->query()->findOrFail($id);
        $role->permissions = $role->permissions->sortBy('id');
        $role->permissionsSelected = $role->permissions()->pluck('permissions.id');
        $role->permissionsShow = $role->permissions->sortBy('id')->implode('name', ', ');
        return response()->json($role);
    }

    public function update(Request $request, $id)
    {
        $role = app(config('vam.models.role'))->query()->findOrFail($id);
        $request->validate([
            'name' => 'required',
        ]);

        $role->update($request->all());
        $role->permissions()->sync($request->get('permissions'));

        activity('Updated Role: ' . $role->id, $request->all(), $role);

        return response()->json([
            'id' => $role->id,
            'status' => 'success',
            'message' => 'Role Updated.',
            'redirectTo' => '/role/' . $role->id . '/show',
        ]);
    }

    public function destroy($id)
    {
        $role = app(config('vam.models.role'))->query()->findOrFail($id);
        $role->delete();

        activity('Deleted Role: ' . $role->id, [], $role);

        return response()->json([
            'id' => $role->id,
            'status' => 'success',
            'message' => 'Role deleted.',
        ]);
    }

    public function checkboxes(Request $request)
    {
        $bootstrapVueCheckboxes = [];
        foreach (app(config('vam.models.role'))->orderBy('name')->get() as $role) {
            $bootstrapVueCheckboxes[] = [
                'value' => $role->id,
                'text' => $role->name,
            ];
        }
        return response()->json($bootstrapVueCheckboxes);
    }
}
