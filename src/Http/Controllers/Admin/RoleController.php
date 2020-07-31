<?php

namespace Wikichua\SAP\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth_admin', 'can:Access Admin Panel']);
        $this->middleware('intend_url')->only(['index', 'read']);
        $this->middleware('can:Create Roles')->only(['create', 'store']);
        $this->middleware('can:Read Roles')->only(['index', 'read']);
        $this->middleware('can:Update Roles')->only(['edit', 'update']);
        $this->middleware('can:Delete Roles')->only('delete');
    }
    
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $models = app(config('sap.models.role'))->query()
                ->filter($request->get('filters', ''))
                ->sorting($request->get('sort', ''),$request->get('direction', ''))
                ->with('roles');
            $paginated = $models->paginate(25);
            foreach ($paginated as $model) {
                $model->actionsView = view('sap::admin.role.actions',compact('model'))->render();
            }
            if ($request->get('filters','') != '') {
                $paginated->appends(['filters' => $request->get('filters','')]);
            }
            if ($request->get('sort','') != '') {
                $paginated->appends(['sort' => $request->get('sort',''), 'direction' => $request->get('direction','asc')]);
            }
            $links = $paginated->onEachSide(5)->links()->render();
            $currentUrl = $request->fullUrl();
            return compact('paginated','links','currentUrl');
        }
        $getUrl = route('role.list');
        $html = [
            ['title' => 'Name', 'data' => 'name', 'sortable' => true],
            ['title' => 'Is Admin', 'data' => 'admin', 'sortable' => true],
            ['title' => '', 'data' => 'actionsView'],
        ];
        return view('sap::admin.role.index', compact('html','getUrl'));
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
