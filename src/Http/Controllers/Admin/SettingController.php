<?php

namespace Wikichua\SAP\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth_admin', 'can:Access Admin Panel']);
        $this->middleware('intend_url')->only(['index', 'read']);
        $this->middleware('can:Create Settings')->only(['create', 'store']);
        $this->middleware('can:Read Settings')->only(['index', 'read']);
        $this->middleware('can:Update Settings')->only(['edit', 'update']);
        $this->middleware('can:Delete Settings')->only('delete');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $models = app(config('sap.models.setting'))->query()
                ->checkBrand()
                ->filter($request->get('filters', ''))
                ->sorting($request->get('sort', ''), $request->get('direction', ''));
            $paginated = $models->paginate($request->get('take', 25));
            foreach ($paginated as $model) {
                $model->actionsView = view('sap::admin.setting.actions', compact('model'))->render();
                $model->value       = is_array($model->value) ? implode('<br>', $model->value) : $model->value;
            }
            if ($request->get('filters', '') != '') {
                $paginated->appends(['filters' => $request->get('filters', '')]);
            }
            if ($request->get('sort', '') != '') {
                $paginated->appends(['sort' => $request->get('sort', ''), 'direction' => $request->get('direction', 'asc')]);
            }
            $links      = $paginated->onEachSide(5)->links()->render();
            $currentUrl = $request->fullUrl();
            return compact('paginated', 'links', 'currentUrl');
        }
        $getUrl = route('setting.list');
        $html   = [
            ['title' => 'Key', 'data' => 'key', 'sortable' => true],
            ['title' => 'Value', 'data' => 'value', 'sortable' => true],
            ['title' => '', 'data' => 'actionsView'],
        ];
        return view('sap::admin.setting.index', compact('html', 'getUrl'));
    }

    public function create(Request $request)
    {
        return view('sap::admin.setting.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'key' => 'required',
        ]);

        if ($request->get('multipleTypes', false) == true) {
            $request->merge(['value' => array_combine($request->get('indexes', []), $request->get('values', []))]);
        }

        $model = app(config('sap.models.setting'))->create($request->all());

        activity('Created Setting: ' . $model->id, $request->all(), $model);

        cache()->forget('setting-' . $model->key);

        return response()->json([
            'status'   => 'success',
            'flash'    => 'Setting Created.',
            'reload'   => false,
            'relist'   => false,
            'redirect' => route('setting.list'),
        ]);
    }

    public function show($id)
    {
        $model = app(config('sap.models.setting'))->query()->findOrFail($id);
        return view('sap::admin.setting.show', compact('model'));
    }

    public function edit(Request $request, $id)
    {
        $model = app(config('sap.models.setting'))->query()->findOrFail($id);
        return view('sap::admin.setting.edit', compact('model'));
    }

    public function update(Request $request, $id)
    {
        $model = app(config('sap.models.setting'))->query()->findOrFail($id);
        $request->validate([
            'key' => 'required',
        ]);

        if ($request->get('multipleTypes', false) == true) {
            $request->merge(['value' => array_combine($request->get('indexes', []), $request->get('values', []))]);
        }

        $model->update($request->all());

        activity('Updated Setting: ' . $model->id, $request->all(), $model);

        cache()->forget('setting-' . $model->key);

        return response()->json([
            'status'   => 'success',
            'flash'    => 'Setting Updated.',
            'reload'   => false,
            'relist'   => false,
            'redirect' => route('setting.edit', [$model->id]),
        ]);
    }

    public function destroy($id)
    {
        $model = app(config('sap.models.setting'))->query()->findOrFail($id);
        $model->delete();

        activity('Deleted Setting: ' . $model->id, [], $model);

        return response()->json([
            'status'   => 'success',
            'flash'    => 'Setting Deleted.',
            'reload'   => false,
            'relist'   => true,
            'redirect' => false,
        ]);
    }
}
