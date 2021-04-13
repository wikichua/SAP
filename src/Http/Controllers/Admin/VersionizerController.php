<?php

namespace Wikichua\SAP\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VersionizerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth_admin', 'can:Access Admin Panel']);
        $this->middleware('intend_url')->only(['index', 'read']);
        $this->middleware('can:Read Versionizers')->only(['index', 'read']);
        $this->middleware('can:Revert Versionizers')->only(['revert']);
        $this->middleware('can:Delete Versionizers')->only('destroy');
        \Breadcrumbs::for('home', function ($trail) {
            $trail->push('Versionizers Listing', route('versionizer.list'));
        });
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $models = app(config('sap.models.versionizer'))->query()
                ->filter($request->get('filters', ''))
                ->sorting($request->get('sort', ''), $request->get('direction', ''));
            $paginated = $models->paginate($request->get('take', 25));
            foreach ($paginated as $model) {
                $model->actionsView = view('sap::admin.versionizer.actions', compact('model'))->render();
                $model->changes = '<code>'.json_encode($model->changes, 1).'</code>';
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
        $getUrl = route('versionizer.list');
        $html = [
            ['title' => 'Created At', 'data' => 'created_at', 'sortable' => true],
            ['title' => 'Mode', 'data' => 'mode', 'sortable' => true],
            ['title' => 'Model', 'data' => 'model', 'sortable' => true],
            ['title' => 'Model ID', 'data' => 'model_id', 'sortable' => true],
            ['title' => 'Changes', 'data' => 'changes', 'sortable' => true],
            ['title' => '', 'data' => 'actionsView'],
        ];
        return view('sap::admin.versionizer.index', compact('html', 'getUrl'));
    }

    public function show($id)
    {
        \Breadcrumbs::for('breadcrumb', function ($trail) use ($id) {
            $trail->parent('home');
            $trail->push('Show Versionizer');
        });
        $model = app(config('sap.models.versionizer'))->query()->findOrFail($id);
        return view('sap::admin.versionizer.show', compact('model'));
    }

    public function revert(Request $request, $id)
    {
        $model = app(config('sap.models.versionizer'))->query()->findOrFail($id);
        $revertModel = app($model->model)->whereId($model->model_id);
        $checkModel = (clone $revertModel)->first();
        if ($checkModel && $model->mode == 'Updated') {
            $revertModel->update($model->data);
        } else {
            if (isset($model->data['deleted_at'])) {
                $revertModel->restore();
            } else {
                $revertModel = $revertModel->create($model->data);
                if (isset($model->data['id'])) {
                    $revertModel->id = $model->data['id'];
                    $revertModel->save();
                }
            }
        }
        return response()->json([
            'status' => 'success',
            'flash' => 'Version Reverted.',
            'reload' => false,
            'relist' => true,
            'redirect' => false,
        ]);
    }

    public function destroy($id)
    {
        $model = app(config('sap.models.versionizer'))->query()->findOrFail($id);
        $model->delete();
        return response()->json([
            'status' => 'success',
            'flash' => 'Version Deleted.',
            'reload' => false,
            'relist' => true,
            'redirect' => false,
        ]);
    }
}
