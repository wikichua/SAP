<?php

namespace Wikichua\SAP\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BrandController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth_admin', 'can:Access Admin Panel']);
        $this->middleware('intend_url')->only(['index', 'read']);
        $this->middleware('can:Read Brands')->only(['index', 'read']);
        $this->middleware('can:Update Brands')->only(['edit', 'update']);

        \Breadcrumbs::for('home', function ($trail) {
            $trail->push('Brand Listing', route('brand.list'));
        });
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $models = app(config('sap.models.brand'))->query()
                ->checkBrand()
                ->filter($request->get('filters', ''))
                ->sorting($request->get('sort', ''), $request->get('direction', ''))
            ;
            $paginated = $models->paginate($request->get('take', 25));
            foreach ($paginated as $model) {
                $model->actionsView = view('sap::admin.brand.actions', compact('model'))->render();
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
        $getUrl = route('brand.list');
        $html = [
            ['title' => 'Brand Name', 'data' => 'name', 'sortable' => true, 'filterable' => true],
            ['title' => 'Domain', 'data' => 'domain', 'sortable' => true, 'filterable' => true],
            ['title' => 'Published Date', 'data' => 'published_at', 'sortable' => false, 'filterable' => true],
            ['title' => 'Expired Date', 'data' => 'expired_at', 'sortable' => false, 'filterable' => true],
            ['title' => 'Status', 'data' => 'status_name', 'sortable' => false, 'filterable' => true],
            ['title' => '', 'data' => 'actionsView'],
        ];

        return view('sap::admin.brand.index', compact('html', 'getUrl'));
    }

    public function show($id)
    {
        \Breadcrumbs::for('breadcrumb', function ($trail) {
            $trail->parent('home');
            $trail->push('Show Brand');
        });
        $model = app(config('sap.models.brand'))->query()->findOrFail($id);

        return view('sap::admin.brand.show', compact('model'));
    }

    public function edit(Request $request, $id)
    {
        \Breadcrumbs::for('breadcrumb', function ($trail) {
            $trail->parent('home');
            $trail->push('Edit Brand');
        });
        $model = app(config('sap.models.brand'))->query()->findOrFail($id);

        return view('sap::admin.brand.edit', compact('model'));
    }

    public function update(Request $request, $id)
    {
        $model = app(config('sap.models.brand'))->query()->findOrFail($id);
        $request->validate([
            'domain' => 'required',
            'published_at' => 'required',
            'expired_at' => 'required',
            'status' => 'required',
        ]);

        $request->merge([
            'updated_by' => auth()->id(),
        ]);

        $model->update($request->input());

        \Cache::forget('brand-'.$model->name);
        sendAlert([
            'brand_id' => 0,
            'link' => $model->readUrl,
            'message' => 'Brand Added. ('.$model->slug.')',
            'sender_id' => auth()->id(),
            'receiver_id' => permissionUserIds('Read Brands'),
            'icon' => $model->menu_icon,
        ]);

        return response()->json([
            'status' => 'success',
            'flash' => 'Brand Updated.',
            'reload' => false,
            'relist' => false,
            'redirect' => route('brand.edit', [$model->id]),
        ]);
    }
}
