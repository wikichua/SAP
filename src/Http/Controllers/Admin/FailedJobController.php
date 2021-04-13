<?php

namespace Wikichua\SAP\Http\Controllers\Admin;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;

class FailedJobController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth_admin', 'can:Access Admin Panel']);
        $this->middleware('intend_url')->only(['index', 'read']);
        $this->middleware('can:Read Failed Jobs')->only(['index', 'read']);
        $this->middleware('can:Retry Failed Jobs')->only(['retry']);
        \Breadcrumbs::for('home', function ($trail) {
            $trail->push('Failed Jobs Listing', route('failed_job.list'));
        });
    }

    public function summary(Request $request)
    {
        $keys = queue_keys();
        $queues = [];
        foreach ($keys as $key) {
            $queues[$key] = Queue::size($key);
        }
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $models = app(config('sap.models.failed_job'))->query()
                ->filter($request->get('filters', ''))
                ->sorting($request->get('sort', ''), $request->get('direction', ''));
            $paginated = $models->paginate($request->get('take', 25));
            foreach ($paginated as $model) {
                $model->actionsView = view('sap::admin.failed_job.actions', compact('model'))->render();
                $model->exception = Str::limit($model->exception, 100);
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
        $getUrl = route('failed_job.list');
        $html = [
            ['title' => 'ID', 'data' => 'id', 'sortable' => true],
            ['title' => 'Failed At', 'data' => 'failed_at', 'sortable' => true],
            ['title' => 'Queue', 'data' => 'queue', 'sortable' => true],
            ['title' => 'Exception', 'data' => 'exception'],
            ['title' => '', 'data' => 'actionsView'],
        ];
        return view('sap::admin.failed_job.index', compact('html', 'getUrl'));
    }

    public function show($id)
    {
        \Breadcrumbs::for('breadcrumb', function ($trail) {
            $trail->parent('home');
            $trail->push('Show Failed Job');
        });
        $model = app(config('sap.models.failed_job'))->query()->findOrFail($id);
        return view('sap::admin.failed_job.show', compact('model'));
    }

    public function retry($id)
    {
        $model = app(config('sap.models.failed_job'))->query()->findOrFail($id);
        Cache::flush();
        Artisan::call('queue:retry', [
            'id' => $model->uuid
        ]);
        activity('Retry queue: ' . $model->id, [], $model);
        return response()->json([
            'status'   => 'success',
            'flash'    => 'Retried Queue.',
            'reload'   => false,
            'relist'   => true,
            'redirect' => false,
        ]);
    }
}
