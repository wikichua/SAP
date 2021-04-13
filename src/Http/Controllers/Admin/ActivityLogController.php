<?php

namespace Wikichua\SAP\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ActivityLogController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth_admin', 'can:Access Admin Panel']);
        $this->middleware('intend_url')->only(['index', 'read']);
        $this->middleware('can:Read Activity Logs')->only(['index', 'read']);
        \Breadcrumbs::for('home', function ($trail) {
            $trail->push('Activities Log Listing', route('activity_log.list'));
        });
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $models = app(config('sap.models.activity_log'))->query()
                ->filter($request->get('filters', ''))
                ->sorting($request->get('sort', ''), $request->get('direction', ''))
                ->with(['user'])
            ;
            $paginated = $models->paginate($request->get('take', 25));
            foreach ($paginated as $model) {
                $model->actionsView = view('sap::admin.activity_log.actions', compact('model'))->render();
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
        $getUrl = route('activity_log.list');
        $html = [
            ['title' => 'Created At', 'data' => 'created_at', 'sortable' => true],
            ['title' => 'User', 'data' => 'user.name', 'sortable' => true],
            ['title' => 'Model ID', 'data' => 'model_id', 'sortable' => true],
            ['title' => 'Model', 'data' => 'model_class', 'sortable' => true],
            ['title' => 'Message', 'data' => 'message'],
            ['title' => '', 'data' => 'actionsView'],
        ];

        return view('sap::admin.activity_log.index', compact('html', 'getUrl'));
    }

    public function show($id)
    {
        \Breadcrumbs::for('breadcrumb', function ($trail) {
            $trail->parent('home');
            $trail->push('Show Activity Log');
        });
        $model = app(config('sap.models.activity_log'))->query()->findOrFail($id);

        return view('sap::admin.activity_log.show', compact('model'));
    }

    public function setRead($id)
    {
        $model = app(config('sap.models.alert'))->query()->findOrFail($id);
        $model->update(['status' => 'r']);

        return $model->link;
    }
}
