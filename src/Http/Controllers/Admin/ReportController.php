<?php

namespace Wikichua\SAP\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Rap2hpoutre\FastExcel\SheetCollection;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth_admin', 'can:Access Admin Panel']);
        $this->middleware('intend_url')->only(['index', 'read']);
        $this->middleware('can:Create Reports')->only(['create', 'store']);
        $this->middleware('can:Read Reports')->only(['index', 'read']);
        $this->middleware('can:Update Reports')->only(['edit', 'update']);
        $this->middleware('can:Delete Reports')->only('delete');
        $this->middleware('can:Export Reports')->only('export');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $models = app(config('sap.models.report'))->query()
                ->checkBrand()
                ->filter($request->get('filters', ''))
                ->sorting($request->get('sort', ''), $request->get('direction', ''));
            $paginated = $models->paginate($request->get('take', 25));
            foreach ($paginated as $model) {
                $model->actionsView = view('sap::admin.report.actions', compact('model'))->render();
                $model->cache_status = Cache::get('report-'.str_slug($model->name)) == null? 'Processing':'Ready';
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
        $getUrl = route('report.list');
        $html = [
            ['title' => 'Name', 'data' => 'name', 'sortable' => true],
            ['title' => 'Status', 'data' => 'status_name', 'sortable' => false, 'filterable' => true],
            ['title' => 'Report Status', 'data' => 'cache_status', 'sortable' => false, 'filterable' => true],
            ['title' => 'Last Run', 'data' => 'generated_at', 'sortable' => false, 'filterable' => true],
            ['title' => 'Next Run', 'data' => 'next_generate_at', 'sortable' => false, 'filterable' => true],
            ['title' => '', 'data' => 'actionsView'],
        ];
        return view('sap::admin.report.index', compact('html', 'getUrl'));
    }

    public function create(Request $request)
    {
        return view('sap::admin.report.create');
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

        $model = app(config('sap.models.report'))->create($request->all());

        activity('Created Report: ' . $model->id, $request->all(), $model);

        Cache::flush();

        return response()->json([
            'status' => 'success',
            'flash' => 'Report Created.',
            'reload' => false,
            'relist' => false,
            // 'redirect' => route('report.list'),
            'redirect' => route('report.show', [$model->id]),
        ]);
    }

    public function show($id)
    {
        $models = [];
        $model = app(config('sap.models.report'))->query()->findOrFail($id);
        $models = Cache::get('report-'.str_slug($model->name), function () use ($model, $models) {
            foreach ($model->queries as $sql) {
                $models[] = array_map(function ($value) {
                    return (array)$value;
                }, \DB::select($sql));
            }
            return $models;
        });
        return view('sap::admin.report.show', compact('model', 'models'));
    }

    public function export($id)
    {
        $models = [];
        $model = app(config('sap.models.report'))->query()->findOrFail($id);
        foreach ($model->queries as $sql) {
            $models[] = array_map(function ($value) {
                return (array)$value;
            }, \DB::select($sql));
        }
        $sheets = new SheetCollection($models);
        return fastexcel()->data($sheets)->download(\Str::studly($model->name).'.xlsx');
    }

    public function edit(Request $request, $id)
    {
        $model = app(config('sap.models.report'))->query()->findOrFail($id);
        return view('sap::admin.report.edit', compact('model'));
    }

    public function update(Request $request, $id)
    {
        $model = app(config('sap.models.report'))->query()->findOrFail($id);

        $request->validate([
            'name' => 'required',
            "status" => "required",
        ]);

        $request->merge([
            'updated_by' => auth()->id(),
        ]);

        $model->update($request->all());

        activity('Updated Report: ' . $model->id, $request->all(), $model);

        Cache::flush();

        return response()->json([
            'status' => 'success',
            'flash' => 'Report Updated.',
            'reload' => false,
            'relist' => false,
            // 'redirect' => route('report.edit', [$model->id]),
            'redirect' => route('report.show', [$model->id]),
        ]);
    }

    public function destroy($id)
    {
        $model = app(config('sap.models.report'))->query()->findOrFail($id);
        $model->delete();

        activity('Deleted Report: ' . $model->id, [], $model);

        Cache::flush();

        return response()->json([
            'status' => 'success',
            'flash' => 'Report Deleted.',
            'reload' => false,
            'relist' => true,
            'redirect' => false,
        ]);
    }
}
