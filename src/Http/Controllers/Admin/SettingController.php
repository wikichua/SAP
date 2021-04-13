<?php

namespace Wikichua\SAP\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth_admin', 'can:Access Admin Panel']);
        $this->middleware('intend_url')->only(['index', 'read']);
        $this->middleware('can:Create Settings')->only(['create', 'store']);
        $this->middleware('can:Read Settings')->only(['index', 'read']);
        $this->middleware('can:Update Settings')->only(['edit', 'update']);
        $this->middleware('can:Delete Settings')->only('destroy');

        $this->middleware('reauth_admin')->only(['edit', 'destroy']);
        \Breadcrumbs::for('home', function ($trail) {
            $trail->push('Setting Listing', route('setting.list'));
        });
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $models = app(config('sap.models.setting'))->query()
                ->checkBrand()
                ->filter($request->get('filters', ''))
                ->sorting($request->get('sort', ''), $request->get('direction', ''))
            ;
            $paginated = $models->paginate($request->get('take', 25));
            foreach ($paginated as $model) {
                $model->actionsView = view('sap::admin.setting.actions', compact('model'))->render();
                $model->valueString = is_array($model->value) ? implode('<br>', $model->value) : $model->value;
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
        $getUrl = route('setting.list');
        $html = [
            ['title' => 'Key', 'data' => 'key', 'sortable' => true],
            ['title' => 'Value', 'data' => 'valueString', 'sortable' => true],
            ['title' => '', 'data' => 'actionsView'],
        ];

        return view('sap::admin.setting.index', compact('html', 'getUrl'));
    }

    public function create(Request $request)
    {
        \Breadcrumbs::for('breadcrumb', function ($trail) {
            $trail->parent('home');
            $trail->push('Create Setting');
        });

        return view('sap::admin.setting.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'key' => 'required',
        ]);

        if (true == $request->get('multipleTypes', false)) {
            $request->merge(['value' => array_combine($request->get('indexes', []), $request->get('values', []))]);
        }

        if (false == $request->has('protected')) {
            $request->merge(['protected' => 0]);
        }

        $model = app(config('sap.models.setting'))->create($request->all());

        cache()->forget('setting-'.$model->key);

        sendAlert([
            'brand_id' => 0,
            'link' => null,
            'message' => 'New Setting Added. ('.$model->name.')',
            'sender_id' => auth()->id(),
            'receiver_id' => permissionUserIds('Read Settings'),
            'icon' => $model->menu_icon,
        ]);

        return response()->json([
            'status' => 'success',
            'flash' => 'Setting Created.',
            'reload' => false,
            'relist' => false,
            'redirect' => route('setting.list'),
        ]);
    }

    public function show($id)
    {
        \Breadcrumbs::for('breadcrumb', function ($trail) {
            $trail->parent('home');
            $trail->push('Show Setting');
        });
        $model = app(config('sap.models.setting'))->query()->findOrFail($id);

        return view('sap::admin.setting.show', compact('model'));
    }

    public function edit(Request $request, $id)
    {
        \Breadcrumbs::for('breadcrumb', function ($trail) {
            $trail->parent('home');
            $trail->push('Edit Setting');
        });
        $model = app(config('sap.models.setting'))->query()->findOrFail($id);

        return view('sap::admin.setting.edit', compact('model'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'key' => 'required',
        ]);

        $model = app(config('sap.models.setting'))->query()->findOrFail($id);

        if (true == $request->get('multipleTypes', false)) {
            $request->merge(['value' => array_combine($request->get('indexes', []), $request->get('values', []))]);
        }

        if (false == $request->has('protected')) {
            $request->merge(['protected' => 0]);
        }

        $model->update($request->all());

        cache()->forget('setting-'.$model->key);

        sendAlert([
            'brand_id' => 0,
            'link' => null,
            'message' => 'Setting Updated. ('.$model->name.')',
            'sender_id' => auth()->id(),
            'receiver_id' => permissionUserIds('Read Settings'),
            'icon' => $model->menu_icon,
        ]);

        return response()->json([
            'status' => 'success',
            'flash' => 'Setting Updated.',
            'reload' => false,
            'relist' => false,
            'redirect' => route('setting.edit', [$model->id]),
        ]);
    }

    public function destroy($id)
    {
        $model = app(config('sap.models.setting'))->query()->findOrFail($id);
        sendAlert([
            'brand_id' => 0,
            'link' => null,
            'message' => 'Setting Deleted. ('.$model->name.')',
            'sender_id' => auth()->id(),
            'receiver_id' => permissionUserIds('Read Settings'),
            'icon' => $model->menu_icon,
        ]);
        $model->delete();

        return response()->json([
            'status' => 'success',
            'flash' => 'Setting Deleted.',
            'reload' => false,
            'relist' => true,
            'redirect' => false,
        ]);
    }
}
