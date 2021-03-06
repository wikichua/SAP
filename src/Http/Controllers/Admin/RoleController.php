<?php

namespace Wikichua\SAP\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth_admin', 'can:Access Admin Panel']);
        $this->middleware('intend_url')->only(['index', 'read']);
        $this->middleware('can:Create Roles')->only(['create', 'store']);
        $this->middleware('can:Read Roles')->only(['index', 'read']);
        $this->middleware('can:Update Roles')->only(['edit', 'update']);
        $this->middleware('can:Delete Roles')->only('destroy');

        $this->middleware('reauth_admin')->only(['edit', 'destroy']);
        \Breadcrumbs::for('home', function ($trail) {
            $trail->push('Role Listing', route('role.list'));
        });
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $models = app(config('sap.models.role'))->query()
                ->checkBrand()
                ->filter($request->get('filters', ''))
                ->sorting($request->get('sort', ''), $request->get('direction', ''))
            ;
            $paginated = $models->paginate($request->get('take', 25));
            foreach ($paginated as $model) {
                $model->actionsView = view('sap::admin.role.actions', compact('model'))->render();
                $permissions = $model->permissions()->pluck('name')->toArray();
                $model->permissionsView = view('sap::admin.role.permissionList', compact('permissions'))->render();
            }
            if ('' != $request->get('filters', '')) {
                $paginated->appends(['filters' => $request->get('filters', '')]);
            }
            if ('' != $request->get('sort', '')) {
                $paginated->appends(['sort' => $request->get('sort', ''), 'direction' => $request->get('direction', 'asc')]);
            }
            $links = $paginated->onEachSide(5)->links()->render();
            $currentUrl = $request->fullUrl();

            return compact('paginated', 'links', 'currentUrl');
        }
        $getUrl = route('role.list');
        $html = [
            ['title' => 'Name', 'data' => 'name', 'sortable' => true],
            ['title' => 'Is Admin', 'data' => 'isAdmin'],
            ['title' => 'Permissions', 'data' => 'permissionsView'],
            ['title' => '', 'data' => 'actionsView'],
        ];

        return view('sap::admin.role.index', compact('html', 'getUrl'));
    }

    public function create(Request $request)
    {
        \Breadcrumbs::for('breadcrumb', function ($trail) {
            $trail->parent('home');
            $trail->push('Create Role');
        });
        $permissions = app(config('sap.models.permission'))->select(['id', 'name', 'group'])->get()->groupBy('group');
        $group_permissions = [];
        foreach ($permissions as $group => $perms) {
            foreach ($perms as $perm) {
                $group_permissions[$group][$perm->id] = $perm->name;
            }
        }

        return view('sap::admin.role.create', compact('group_permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'admin' => 'required',
            'permissions' => 'required',
        ]);

        $request->merge([
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
            'permissions' => array_values($request->get('permissions', [])),
        ]);

        $model = app(config('sap.models.role'))->create($request->all());
        $model->permissions()->sync($request->get('permissions'));

        sendAlert([
            'brand_id' => 0,
            'link' => $model->readUrl,
            'message' => 'New Role Added. ('.$model->name.')',
            'sender_id' => auth()->id(),
            'receiver_id' => permissionUserIds('Read Roles'),
            'icon' => $model->menu_icon,
        ]);

        return response()->json([
            'status' => 'success',
            'flash' => 'Role Created.',
            'reload' => false,
            'relist' => false,
            'redirect' => route('role.list'),
        ]);
    }

    public function show($id)
    {
        \Breadcrumbs::for('breadcrumb', function ($trail) {
            $trail->parent('home');
            $trail->push('Show Role');
        });
        $model = app(config('sap.models.role'))->query()->findOrFail($id);

        return view('sap::admin.role.show', compact('model'));
    }

    public function edit(Request $request, $id)
    {
        \Breadcrumbs::for('breadcrumb', function ($trail) {
            $trail->parent('home');
            $trail->push('Edit Role');
        });
        $model = app(config('sap.models.role'))->query()->findOrFail($id);
        $permissions = app(config('sap.models.permission'))->select(['id', 'name', 'group'])->get()->groupBy('group');
        $group_permissions = [];
        foreach ($permissions as $group => $perms) {
            foreach ($perms as $perm) {
                $group_permissions[$group][$perm->id] = $perm->name;
            }
        }

        return view('sap::admin.role.edit', compact('model', 'group_permissions'));
    }

    public function update(Request $request, $id)
    {
        $model = app(config('sap.models.role'))->query()->findOrFail($id);

        $request->validate([
            'name' => 'required',
            'admin' => 'required',
        ]);

        $request->merge([
            'updated_by' => auth()->id(),
            'permissions' => array_values($request->get('permissions', [])),
        ]);

        $model->update($request->all());
        $model->permissions()->sync($request->get('permissions'));

        sendAlert([
            'brand_id' => 0,
            'link' => $model->readUrl,
            'message' => 'Role Updated. ('.$model->name.')',
            'sender_id' => auth()->id(),
            'receiver_id' => permissionUserIds('Read Roles'),
            'icon' => $model->menu_icon,
        ]);

        return response()->json([
            'status' => 'success',
            'flash' => 'Role Updated.',
            'reload' => false,
            'relist' => false,
            'redirect' => route('role.edit', [$model->id]),
        ]);
    }

    public function destroy($id)
    {
        $model = app(config('sap.models.role'))->query()->findOrFail($id);
        $model->permissions()->sync([]);
        sendAlert([
            'brand_id' => 0,
            'link' => null,
            'message' => 'New Role Deleted. ('.$model->name.')',
            'sender_id' => auth()->id(),
            'receiver_id' => permissionUserIds('Read Roles'),
            'icon' => $model->menu_icon,
        ]);
        $model->delete();

        return response()->json([
            'status' => 'success',
            'flash' => 'Role Deleted.',
            'reload' => false,
            'relist' => true,
            'redirect' => false,
        ]);
    }
}
