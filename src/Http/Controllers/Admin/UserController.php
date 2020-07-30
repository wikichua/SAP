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
            $paginated = $models->paginate(1);
            foreach ($paginated as $model) {
                $model->actionsView = view('sap::admin.user.actions',compact('model'))->render();
            }
            if ($request->get('filters','') != '') {
                $paginated->appends(['filters' => $request->get('filters','')]);
            }
            if ($request->get('sort','') != '') {
                $paginated->appends(['sort' => $request->get('sort',''), 'direction' => $request->get('direction','asc')]);
            }
            $links = $paginated->onEachSide(10)->links()->render();
            return compact('paginated','links');
        }
        $getUrl = route('user.list');
        $html = [
            ['title' => 'Name', 'data' => 'name', 'sortable' => true],
            ['title' => 'Email Address', 'data' => 'email', 'sortable' => true],
            ['title' => 'Roles', 'data' => 'roles_string'],
            ['title' => '', 'data' => 'actionsView'],
        ];
        return view('sap::admin.user.index', compact('html','getUrl'));
    }

    public function create(Request $request)
    {
        $roles = app(config('sap.models.role'))->all()->sortBy('name');
        return view('sap::admin.user.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'type' => 'required',
            'roles' => 'required',
        ]);

        $request->merge([
            'password' => bcrypt($request->get('password')),
        ]);

        $user = app(config('sap.models.user'))->create($request->all());
        $user->roles()->sync($request->get('roles'));

        activity('Created User: ' . $user->id, $request->all(), $user);

        return response()->json([
            'id' => $user->id,
            'status' => 'success',
            'message' => 'User created.',
            'redirectTo' => '/user/' . $user->id . '/show',
        ]);
    }

    public function show($id)
    {
        $model = app(config('sap.models.user'))->query()->findOrFail($id);
        $model->rolesShow = $model->roles->sortBy('name')->implode('name', ', ');
        $model->rolesSelected = $model->roles()->pluck('roles.id');
        return response()->json($user);
    }

    public function edit(Request $request, $id)
    {
        $model = app(config('sap.models.user'))->query()->findOrFail($id);
        $roles = app(config('sap.models.role'))->all()->sortBy('name');
        return view('sap::admin.user.update', compact('roles', 'model'));
    }

    public function update(Request $request, $id)
    {
        $user = app(config('sap.models.user'))->query()->findOrFail($id);
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'type' => 'required',
            'roles' => 'required',
        ]);

        $user->update($request->all());
        $user->roles()->sync($request->get('roles'));

        activity('Updated User: ' . $user->id, $request->all(), $user);

        return response()->json([
            'id' => $user->id,
            'status' => 'success',
            'message' => 'User Updated.',
            'redirectTo' => '/user/' . $user->id . '/edit',
        ]);
    }

    public function updatePassword(Request $request, $id)
    {
        $user = app(config('sap.models.user'))->query()->findOrFail($id);
        $request->validate([
            'password' => 'required|confirmed',
            'password_confirmation' => 'required',
        ]);

        $request->merge([
            'password' => bcrypt($request->get('password')),
        ]);

        $user->update($request->all());

        activity('Updated User Password: ' . $user->id, $request->all(), $user);

        return response()->json([
            'id' => $user->id,
            'status' => 'success',
            'message' => 'User Password Updated.',
            'redirectTo' => '/user',
        ]);
    }

    public function destroy($id)
    {
        $user = app(config('sap.models.user'))->query()->findOrFail($id);
        $user->delete();

        activity('Deleted User: ' . $user->id, [], $user);

        return response()->json([
            'id' => $user->id,
            'status' => 'success',
            'message' => 'User deleted.',
        ]);
    }
}
