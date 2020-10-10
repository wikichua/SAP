<?php

namespace Wikichua\SAP\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class NavController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth_admin', 'can:Access Admin Panel']);
        $this->middleware('intend_url')->only(['index', 'read', 'preview']);
        $this->middleware('can:Create Navs')->only(['create', 'store']);
        $this->middleware('can:Read Navs')->only(['index', 'read', 'preview']);
        $this->middleware('can:Update Navs')->only(['edit', 'update']);
        $this->middleware('can:Delete Navs')->only('delete');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $models = app(config('sap.models.nav'))->query()
                ->with('brand')
                ->checkBrand()
                ->filter($request->get('filters', ''))
                ->sorting($request->get('sort', ''), $request->get('direction', ''));
            $paginated = $models->paginate($request->get('take', 25));
            foreach ($paginated as $model) {
                $model->actionsView = view('sap::admin.nav.actions', compact('model'))->render();
                $model->link = '<a href="'.route_slug(strtolower($model->brand->name).'.page', $model->route_slug, $model->route_params, $model->locale).'" target="_blank">'.$model->name.'</a>';
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
        $getUrl = route('nav.list');
        $html   = [
            ['title' => 'Brand', 'data' => 'brand.name', 'sortable' => false],
            ['title' => 'Link', 'data' => 'link', 'sortable' => false],
            ['title' => 'Group Slug', 'data' => 'group_slug', 'sortable' => true],
            ['title' => 'Locale', 'data' => 'locale', 'sortable' => true],
            ['title' => 'Ordering', 'data' => 'seq', 'sortable' => true],
            ['title' => 'Status', 'data' => 'status_name', 'sortable' => false, 'filterable' => true],
            ['title' => 'Created Date', 'data' => 'created_at', 'sortable' => false, 'filterable' => true],
            ['title' => '', 'data' => 'actionsView'],
        ];
        return view('sap::admin.nav.index', compact('html', 'getUrl'));
    }

    public function create(Request $request)
    {
        return view('sap::admin.nav.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'brand_id'   => 'required',
            'name'     => 'required',
            'locale'     => 'required',
            'group_slug' => 'required',
            "route_slug" => "required",
            "status"     => "required",
            'seq'       => 'required',
        ]);

        $request->merge([
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        $model = app(config('sap.models.nav'))->create($request->all());

        activity('Created Nav: ' . $model->id, $request->all(), $model);

        Cache::flush();

        return response()->json([
            'status'   => 'success',
            'flash'    => 'Nav Created.',
            'reload'   => false,
            'relist'   => false,
            'redirect' => route('nav.list'),
            // 'redirect' => route('nav.show', [$model->id]),
        ]);
    }

    public function show($id)
    {
        $model = app(config('sap.models.nav'))->query()->findOrFail($id);
        return view('sap::admin.nav.show', compact('model'));
    }

    public function replicate($id)
    {
        $model    = app(config('sap.models.nav'))->query()->findOrFail($id);
        $newModel = $model->replicate();
        $newModel->push();
        $newModel->locale = null;
        $newModel->save();
        activity('Replicated Nav: ' . $newModel->id, [], $newModel);
        Cache::flush();
        return response()->json([
            'status'   => 'success',
            'flash'    => 'Nav Replicated.',
            'reload'   => false,
            'relist'   => false,
            'redirect' => route('nav.edit', [$newModel->id]),
        ]);
    }

    public function edit(Request $request, $id)
    {
        $model = app(config('sap.models.nav'))->query()->findOrFail($id);
        return view('sap::admin.nav.edit', compact('model'));
    }

    public function update(Request $request, $id)
    {
        $model = app(config('sap.models.nav'))->query()->findOrFail($id);

        $request->validate([
            'brand_id'   => 'required',
            'name'   => 'required',
            'locale'     => 'required',
            'group_slug' => 'required',
            "route_slug" => "required",
            "status"     => "required",
            'seq'       => 'required',
        ]);

        $request->merge([
            'updated_by' => auth()->id(),
        ]);

        $model->update($request->all());

        activity('Updated Nav: ' . $model->id, $request->all(), $model);

        Cache::flush();

        return response()->json([
            'status'   => 'success',
            'flash'    => 'Nav Updated.',
            'reload'   => false,
            'relist'   => false,
            'redirect' => route('nav.edit', [$model->id]),
            // 'redirect' => route('nav.show', [$model->id]),
        ]);
    }

    public function destroy($id)
    {
        $model = app(config('sap.models.nav'))->query()->findOrFail($id);
        $model->delete();

        activity('Deleted Nav: ' . $model->id, [], $model);

        Cache::flush();

        return response()->json([
            'status'   => 'success',
            'flash'    => 'Nav Deleted.',
            'reload'   => false,
            'relist'   => true,
            'redirect' => false,
        ]);
    }

    public function pages($brand_id)
    {
        $pages = [];
        if ($brand_id != '') {
            $slugs = app(config('sap.models.page'))->query()
                ->where('brand_id', $brand_id)
                ->where('status', 'A')
                ->pluck('slug', 'name');
            foreach ($slugs as $name => $slug) {
                $pages[$slug] = $name;
            }
        }
        return response()->json($pages);
    }

    public function orderable(Request $request,$orderable = '')
    {
        if ($request->ajax()) {
            $models = app(config('sap.models.nav'))->query()
                ->checkBrand()->orderBy('seq');
            if ($orderable != '') {
                $models->where('group_slug',$orderable);
            }
            $paginated['data'] = $models->take(100)->get();
            return compact('paginated');
        }
        $getUrl = route('nav.orderable',$orderable);
        $actUrl = route('nav.orderableUpdate',$orderable);
        $html = [
            ['title' => 'ID', 'data' => 'id'],
            ['title' => 'Group Slug', 'data' => 'group_slug'],
            ['title' => 'Name', 'data' => 'name'],
        ];
        return view('sap::admin.nav.orderable', compact('html', 'getUrl', 'actUrl'));
    }

    public function orderableUpdate(Request $request,$orderable = '')
    {
        $newRow = $request->get('newRow');
        $models = app(config('sap.models.nav'))->query()->select('id')
            ->checkBrand()->orderByRaw('FIELD(id,'.$newRow.')');
        if ($orderable != '') {
            $models->where('group_slug',$orderable);
        }
        $models = $models->whereIn('id',explode(',', $newRow))->take(100)->get();
        foreach ($models as $seq => $model) {
            $model->seq = $seq+1;
            $model->save();
        }
        activity('Updated Nav: ' . $model->id, $request->input(), $model, $model);

        return response()->json([
            'status' => 'success',
            'flash' => 'Nav Reordered.',
            'reload' => false,
            'relist' => true,
            'redirect' => false,
        ]);
    }
}
