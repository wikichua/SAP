<?php

namespace Wikichua\SAP\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth_admin', 'can:Access Admin Panel']);
        $this->middleware('intend_url')->only(['index', 'read']);
        $this->middleware('can:Create Permissions')->only(['create', 'store']);
        $this->middleware('can:Read Permissions')->only(['index', 'read']);
        $this->middleware('can:Update Permissions')->only(['edit', 'update']);
        $this->middleware('can:Delete Permissions')->only('destroy');

        $this->middleware('reauth_admin')->only(['edit','destroy']);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $models = app(config('sap.models.permission'))->query()
                ->checkBrand()
                ->filter($request->get('filters', ''))
                ->sorting($request->get('sort', ''), $request->get('direction', ''));
            $paginated = $models->paginate($request->get('take', 25));
            foreach ($paginated as $model) {
                $model->actionsView = view('sap::admin.permission.actions', compact('model'))->render();
            }
            if ($request->get('filters', '') != '') {
                $paginated->appends(['filters' => $request->get('filters', '')]);
            }
            if ($request->get('sort', '') != '') {
                $paginated->appends(['sort' => $request->get('sort', ''), 'direction' => $request->get('direction', 'asc')]);
            }
            $links = $paginated->onEachSide(5)->links()->render();
            $currentUrl = $request->fullUrl();
            return compact('paginated', 'links', 'currentUrl');
        }
        $getUrl = route('permission.list');
        $html = [
            ['title' => 'Group', 'data' => 'group', 'sortable' => true],
            ['title' => 'Name', 'data' => 'name', 'sortable' => true],
            ['title' => '', 'data' => 'actionsView'],
        ];
        return view('sap::admin.permission.index', compact('html', 'getUrl'));
    }

    public function create(Request $request)
    {
        return view('sap::admin.permission.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'group' => 'required',
        ]);

        $request->merge([
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        $model = app(config('sap.models.permission'))->create($request->all());

        sendAlert([
            'brand_id' => 0,
            'link' => $model->readUrl,
            'message' => 'New Permission Added. ('.$model->name.')',
            'sender_id' => auth()->id(),
            'receiver_id' => 0,
            'icon' => 'fas fa-lock'
        ]);

        return response()->json([
            'status' => 'success',
            'flash' => 'Permission Created.',
            'reload' => false,
            'relist' => false,
            'redirect' => route('permission.list'),
        ]);
    }

    public function show($id)
    {
        $model = app(config('sap.models.permission'))->query()->findOrFail($id);
        return view('sap::admin.permission.show', compact('model'));
    }

    public function edit(Request $request, $id)
    {
        $model = app(config('sap.models.permission'))->query()->findOrFail($id);
        return view('sap::admin.permission.edit', compact('model'));
    }

    public function update(Request $request, $id)
    {
        $model = app(config('sap.models.permission'))->query()->findOrFail($id);

        $request->validate([
            'name' => 'required',
            'group' => 'required',
        ]);

        $request->merge([
            'updated_by' => auth()->id(),
        ]);

        $model->update($request->all());

        sendAlert([
            'brand_id' => 0,
            'link' => $model->readUrl,
            'message' => 'Permission Updated. ('.$model->name.')',
            'sender_id' => auth()->id(),
            'receiver_id' => 0,
            'icon' => 'fas fa-lock'
        ]);

        return response()->json([
            'status' => 'success',
            'flash' => 'Permission Updated.',
            'reload' => false,
            'relist' => false,
            'redirect' => route('permission.edit', [$model->id]),
        ]);
    }

    public function destroy($id)
    {
        $model = app(config('sap.models.permission'))->query()->findOrFail($id);
        $model->delete();

        sendAlert([
            'brand_id' => 0,
            'link' => null,
            'message' => 'Permission Deleted. ('.$model->name.')',
            'sender_id' => auth()->id(),
            'receiver_id' => 0,
            'icon' => 'fas fa-lock'
        ]);

        return response()->json([
            'status' => 'success',
            'flash' => 'Permission Deleted.',
            'reload' => false,
            'relist' => true,
            'redirect' => false,
        ]);
    }
}
