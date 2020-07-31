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
                ->sorting($request->get('sort', ''),$request->get('direction', ''));
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
            ['title' => 'Is Admin', 'data' => 'isAdmin'],
            ['title' => '', 'data' => 'actionsView'],
        ];
        return view('sap::admin.role.index', compact('html','getUrl'));
    }

    public function create(Request $request)
    {
        $roles = app(config('sap.models.role'))->pluck('name','id')->sortBy('name');
        return view('sap::admin.role.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'admin' => 'required',
        ]);

        $request->merge([
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        $model = app(config('sap.models.role'))->create($request->all());

        activity('Created Role: ' . $model->id, $request->all(), $model);

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
        $model = app(config('sap.models.role'))->query()->findOrFail($id);
        return view('sap::admin.role.show', compact('model'));
    }

    public function edit(Request $request, $id)
    {
        $model = app(config('sap.models.role'))->query()->findOrFail($id);
        return view('sap::admin.role.edit', compact('model'));
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
        ]);

        $model->update($request->all());

        activity('Updated Role: ' . $model->id, $request->all(), $model);

        return response()->json([
            'status' => 'success',
            'flash' => 'Role Updated.',
            'reload' => false,
            'relist' => false,
            'redirect' => route('role.edit',[$model->id]),
        ]);
    }

    public function destroy($id)
    {
        $model = app(config('sap.models.role'))->query()->findOrFail($id);
        $model->delete();

        activity('Deleted Role: ' . $model->id, [], $model);

        return response()->json([
            'status' => 'success',
            'flash' => 'Role Deleted.',
            'reload' => false,
            'relist' => true,
            'redirect' => false,
        ]);
    }
}
