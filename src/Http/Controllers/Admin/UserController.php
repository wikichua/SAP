<?php

namespace Wikichua\SAP\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth_admin', 'can:Access Admin Panel']);
        $this->middleware('intend_url')->only(['index', 'read']);
        $this->middleware('can:Create Users')->only(['create', 'store']);
        $this->middleware('can:Read Users')->only(['index', 'read']);
        $this->middleware('can:Update Users')->only(['edit', 'update']);
        $this->middleware('can:Update Users Password')->only(['editPassword', 'updatePassword']);
        $this->middleware('can:Delete Users')->only('delete');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $models = app(config('sap.models.user'))->query()
                ->where('id', '!=', 1)
                ->filter($request->get('filters', ''))
                ->sorting($request->get('sort', ''),$request->get('direction', ''))
                ->with('roles');
            $paginated = $models->paginate(25);
            foreach ($paginated as $model) {
                $model->actionsView = view('sap::admin.user.actions',compact('model'))->render();
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
        $getUrl = route('user.list');
        $html = [
            ['title' => 'Name', 'data' => 'name', 'sortable' => true],
            ['title' => 'Email', 'data' => 'email', 'sortable' => true],
            ['title' => 'Type', 'data' => 'type', 'sortable' => true],
            ['title' => 'Roles', 'data' => 'roles_string'],
            ['title' => '', 'data' => 'actionsView'],
        ];
        return view('sap::admin.user.index', compact('html','getUrl'));
    }

    public function create(Request $request)
    {
        $roles = app(config('sap.models.role'))->pluck('name','id')->sortBy('name');
        return view('sap::admin.user.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'type' => 'required',
            'roles' => 'required',
            'password_confirmation' => 'required',
            'password' => ['required','confirmed'],
        ]);

        $request->merge([
            'password' => bcrypt($request->get('password')),
            'roles' => array_values($request->get('roles',[])),
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        $model = app(config('sap.models.user'))->create($request->all());
        $model->roles()->sync($request->get('roles'));

        activity('Created User: ' . $model->id, $request->all(), $model);

        return response()->json([
            'status' => 'success',
            'flash' => 'User Created.',
            'reload' => false,
            'relist' => false,
            'redirect' => route('user.list'),
        ]);
    }

    public function show($id)
    {
        $model = app(config('sap.models.user'))->query()->findOrFail($id);
        $model->rolesShow = $model->roles->sortBy('name')->implode('name', ', ');
        $model->rolesSelected = $model->roles()->pluck('roles.id');
        return response()->json($model);
    }

    public function edit(Request $request, $id)
    {
        $model = app(config('sap.models.user'))->query()->findOrFail($id);
        $roles = app(config('sap.models.role'))->pluck('name','id')->sortBy('name');
        return view('sap::admin.user.edit', compact('roles', 'model'));
    }

    public function update(Request $request, $id)
    {
        $model = app(config('sap.models.user'))->query()->findOrFail($id);

        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'type' => 'required',
            'roles' => 'required',
        ]);

        $request->merge([
            'roles' => array_values($request->get('roles',[])),
            'updated_by' => auth()->id(),
        ]);

        $model->update($request->all());
        $model->roles()->sync($request->get('roles'));

        activity('Updated User: ' . $model->id, $request->all(), $model);

        return response()->json([
            'status' => 'success',
            'flash' => 'User Updated.',
            'reload' => false,
            'relist' => false,
            'redirect' => route('user.edit',[$model->id]),
        ]);
    }

    public function editPassword(Request $request, $id)
    {
        return view('sap::admin.user.editPassword',compact('id'));
    }

    public function updatePassword(Request $request, $id)
    {
        $model = app(config('sap.models.user'))->query()->findOrFail($id);
        $request->validate([
            'password' => 'required|confirmed',
            'password_confirmation' => 'required',
        ]);

        $request->merge([
            'password' => bcrypt($request->get('password')),
            'updated_by' => auth()->id(),
        ]);

        $model->update($request->all());

        activity('Updated User Password: ' . $model->id, $request->all(), $model);

        return response()->json([
            'status' => 'success',
            'flash' => 'User Password Updated.',
            'reload' => true,
            'relist' => false,
            'redirect' => false,
        ]);
    }

    public function destroy($id)
    {
        $model = app(config('sap.models.user'))->query()->findOrFail($id);
        $model->delete();

        activity('Deleted User: ' . $model->id, [], $model);

        return response()->json([
            'status' => 'success',
            'flash' => 'User Deleted.',
            'reload' => false,
            'relist' => true,
            'redirect' => false,
        ]);
    }
}
