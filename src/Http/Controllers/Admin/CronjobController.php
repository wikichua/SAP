<?php

namespace Wikichua\SAP\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CronjobController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth_admin', 'can:Access Admin Panel']);
        $this->middleware('intend_url')->only(['index', 'read']);
        $this->middleware('can:Create Cronjobs')->only(['create', 'store']);
        $this->middleware('can:Read Cronjobs')->only(['index', 'read']);
        $this->middleware('can:Update Cronjobs')->only(['edit', 'update']);
        $this->middleware('can:Delete Cronjobs')->only('destroy');

        $this->middleware('reauth_admin')->only(['edit','destroy']);

        \Breadcrumbs::for('home', function ($trail) {
            $trail->push('Cron Jobs Listing', route('cronjob.list'));
        });
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $models = app(config('sap.models.cronjob'))->query()
                ->checkBrand()
                ->filter($request->get('filters', ''))
                ->sorting($request->get('sort', ''), $request->get('direction', ''));
            $paginated = $models->paginate($request->get('take', 25));
            foreach ($paginated as $model) {
                $model->actionsView = view('sap::admin.cronjob.actions', compact('model'))->render();
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
        $getUrl = route('cronjob.list');
        $html = [
            ['title' => 'Name', 'data' => 'name', 'sortable' => true],
            ['title' => 'Timezone', 'data' => 'timezone', 'sortable' => false, 'filterable' => true],
            ['title' => 'Frequency', 'data' => 'frequency', 'sortable' => false, 'filterable' => true],
            ['title' => 'Status', 'data' => 'status_name', 'sortable' => false, 'filterable' => true],
            ['title' => 'Created Date', 'data' => 'created_at', 'sortable' => false, 'filterable' => true],
            ['title' => '', 'data' => 'actionsView'],
        ];
        return view('sap::admin.cronjob.index', compact('html', 'getUrl'));
    }

    public function create(Request $request)
    {
        \Breadcrumbs::for('breadcrumb', function ($trail) {
            $trail->parent('home');
            $trail->push('Create Cron Job');
        });
        return view('sap::admin.cronjob.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            "status" => "required",
        ]);

        $request->merge([
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        $model = app(config('sap.models.cronjob'))->create($request->all());

        return response()->json([
            'status' => 'success',
            'flash' => 'Cronjob Created.',
            'reload' => false,
            'relist' => false,
            'redirect' => route('cronjob.list'),
        ]);
    }

    public function show($id)
    {
        \Breadcrumbs::for('breadcrumb', function ($trail) {
            $trail->parent('home');
            $trail->push('Show Cron Job');
        });
        $model = app(config('sap.models.cronjob'))->query()->findOrFail($id);
        return view('sap::admin.cronjob.show', compact('model'));
    }

    public function edit(Request $request, $id)
    {
        \Breadcrumbs::for('breadcrumb', function ($trail) {
            $trail->parent('home');
            $trail->push('Edit Cron Job');
        });
        $model = app(config('sap.models.cronjob'))->query()->findOrFail($id);
        return view('sap::admin.cronjob.edit', compact('model'));
    }

    public function update(Request $request, $id)
    {
        $model = app(config('sap.models.cronjob'))->query()->findOrFail($id);

        $request->validate([
            'name' => 'required',
            "status" => "required",
        ]);

        $request->merge([
            'updated_by' => auth()->id(),
        ]);

        $model->update($request->all());

        return response()->json([
            'status' => 'success',
            'flash' => 'Cronjob Updated.',
            'reload' => false,
            'relist' => false,
            'redirect' => route('cronjob.edit', [$model->id]),
        ]);
    }

    public function destroy($id)
    {
        $model = app(config('sap.models.cronjob'))->query()->findOrFail($id);
        $model->delete();
        return response()->json([
            'status' => 'success',
            'flash' => 'Cronjob Deleted.',
            'reload' => false,
            'relist' => true,
            'redirect' => false,
        ]);
    }
}
