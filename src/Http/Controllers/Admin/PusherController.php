<?php

namespace Wikichua\SAP\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class PusherController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth_admin', 'can:Access Admin Panel']);
        $this->middleware('intend_url')->only(['index', 'read', 'preview']);
        $this->middleware('can:Create Pushers')->only(['create', 'store']);
        $this->middleware('can:Read Pushers')->only(['index', 'read', 'preview']);
        $this->middleware('can:Update Pushers')->only(['edit', 'update']);
        $this->middleware('can:Delete Pushers')->only('destroy');
        $this->middleware('can:Push Pushers')->only('push');

        $this->middleware('reauth_admin')->only(['edit','destroy']);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $models = app(config('sap.models.pusher'))->query()
                ->with('brand')
                ->checkBrand()
                ->filter($request->get('filters', ''))
                ->sorting($request->get('sort', ''), $request->get('direction', ''));
            $paginated = $models->paginate($request->get('take', 25));
            foreach ($paginated as $model) {
                $model->actionsView = view('sap::admin.pusher.actions', compact('model'))->render();
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
        $getUrl = route('pusher.list');
        $html   = [
            ['title' => 'Brand', 'data' => 'brand.name', 'sortable' => true],
            ['title' => 'Title', 'data' => 'title', 'sortable' => true],
            ['title' => 'Locale', 'data' => 'locale', 'sortable' => true],
            ['title' => 'Event', 'data' => 'event_name', 'sortable' => true],
            ['title' => 'Scheduled Date', 'data' => 'scheduled_at', 'sortable' => false, 'filterable' => true],
            ['title' => 'Status', 'data' => 'status_name', 'sortable' => false, 'filterable' => true],
            ['title' => 'Created Date', 'data' => 'created_at', 'sortable' => false, 'filterable' => true],
            ['title' => '', 'data' => 'actionsView'],
        ];
        return view('sap::admin.pusher.index', compact('html', 'getUrl'));
    }

    public function create(Request $request)
    {
        return view('sap::admin.pusher.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'locale'       => 'required',
            'event'         => 'required',
            'title'         => 'required',
            "message" => "required",
            "timeout"   => "required",
            "scheduled_at"       => "required",
            "status"       => "required",
        ]);

        $request->merge([
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        $model = app(config('sap.models.pusher'))->create($request->all());

        return response()->json([
            'status'   => 'success',
            'flash'    => 'Pusher Created.',
            'reload'   => false,
            'relist'   => false,
            'redirect' => route('pusher.list'),
            // 'redirect' => route('pusher.show', [$model->id]),
        ]);
    }

    public function show($id)
    {
        $model = app(config('sap.models.pusher'))->query()->findOrFail($id);
        return view('sap::admin.pusher.show', compact('model'));
    }

    public function edit(Request $request, $id)
    {
        $model = app(config('sap.models.pusher'))->query()->findOrFail($id);
        return view('sap::admin.pusher.edit', compact('model'));
    }

    public function update(Request $request, $id)
    {
        $model = app(config('sap.models.pusher'))->query()->findOrFail($id);

        $request->validate([
            'locale'       => 'required',
            'event'         => 'required',
            'title'         => 'required',
            "message" => "required",
            "timeout"   => "required",
            "scheduled_at"       => "required",
            "status"       => "required",
        ]);

        $request->merge([
            'updated_by' => auth()->id(),
        ]);

        $model->update($request->all());

        return response()->json([
            'status'   => 'success',
            'flash'    => 'Pusher Updated.',
            'reload'   => false,
            'relist'   => false,
            'redirect' => route('pusher.edit', [$model->id]),
            // 'redirect' => route('pusher.show', [$model->id]),
        ]);
    }

    public function destroy($id)
    {
        $model = app(config('sap.models.pusher'))->query()->findOrFail($id);
        $model->delete();

        return response()->json([
            'status'   => 'success',
            'flash'    => 'Pusher Deleted.',
            'reload'   => false,
            'relist'   => true,
            'redirect' => false,
        ]);
    }

    public function push($id)
    {
        $model = app(config('sap.models.pusher'))->query()->findOrFail($id);
        $channel = '';
        if ($model->brand) {
            $channel = strtolower($model->brand->name);
        }
        pushered($model->toArray(), $channel, $model->event, $model->locale);
        return response()->json([
            'status'   => 'success',
            'flash'    => 'Pusher Message Pushed.',
            'reload'   => false,
            'relist'   => true,
            'redirect' => false,
        ]);
    }
}
