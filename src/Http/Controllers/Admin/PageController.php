<?php

namespace Wikichua\SAP\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class PageController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth_admin', 'can:Access Admin Panel']);
        $this->middleware('intend_url')->only(['index', 'read', 'preview']);
        $this->middleware('can:Create Pages')->only(['create', 'store']);
        $this->middleware('can:Read Pages')->only(['index', 'read', 'preview']);
        $this->middleware('can:Update Pages')->only(['edit', 'update']);
        $this->middleware('can:Delete Pages')->only('delete');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $models = app(config('sap.models.page'))->query()
                ->filter($request->get('filters', ''))
                ->sorting($request->get('sort', ''), $request->get('direction', ''));
            $paginated = $models->paginate(25);
            foreach ($paginated as $model) {
                $model->actionsView = view('sap::admin.page.actions', compact('model'))->render();
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
        $getUrl = route('page.list');
        $html   = [
            ['title' => 'Name', 'data' => 'name', 'sortable' => true],
            ['title' => 'Locale', 'data' => 'locale', 'sortable' => true],
            ['title' => 'Slug', 'data' => 'slug', 'sortable' => true],
            ['title' => 'Template', 'data' => 'template', 'sortable' => true],
            ['title' => 'Published Date', 'data' => 'published_at', 'sortable' => false, 'filterable' => true],
            ['title' => 'Expired Date', 'data' => 'expired_at', 'sortable' => false, 'filterable' => true],
            ['title' => 'Status', 'data' => 'status_name', 'sortable' => false, 'filterable' => true],
            ['title' => 'Created Date', 'data' => 'created_at', 'sortable' => false, 'filterable' => true],
            ['title' => '', 'data' => 'actionsView'],
        ];
        return view('sap::admin.page.index', compact('html', 'getUrl'));
    }

    public function create(Request $request)
    {
        return view('sap::admin.page.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'brand_id'     => 'required',
            'locale'       => 'required',
            'name'         => 'required',
            'slug'         => 'required',
            "published_at" => "required",
            "expired_at"   => "required",
            "status"       => "required",
        ]);

        $request->merge([
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        $model = app(config('sap.models.page'))->create($request->all());

        activity('Created Page: ' . $model->id, $request->all(), $model);

        Cache::flush();

        return response()->json([
            'status'   => 'success',
            'flash'    => 'Page Created.',
            'reload'   => false,
            'relist'   => false,
            'redirect' => route('page.list'),
            // 'redirect' => route('page.show', [$model->id]),
        ]);
    }

    public function show($id)
    {
        $model = app(config('sap.models.page'))->query()->findOrFail($id);
        return view('sap::admin.page.show', compact('model'));
    }

    public function replicate($id)
    {
        $model = app(config('sap.models.page'))->query()->findOrFail($id);
        $newModel = $model->replicate();
        $newModel->push();
        $newModel->locale = null;
        $newModel->save();
        activity('Deleted Page: ' . $newModel->id, [], $newModel);
        Cache::flush();
        return response()->json([
            'status'   => 'success',
            'flash'    => 'Page Replicated.',
            'reload'   => false,
            'relist'   => false,
            'redirect' => route('page.edit', [$newModel->id]),
        ]);
    }

    public function preview($id)
    {
        $model = app(config('sap.models.page'))->query()->findOrFail($id);
        $brandName = strtolower($model->brand->name);
        \View::addNamespace($brandName, base_path('brand/'.$brandName));
        return view($brandName.'::pages.page', compact('model'));
    }

    public function edit(Request $request, $id)
    {
        $model = app(config('sap.models.page'))->query()->findOrFail($id);
        return view('sap::admin.page.edit', compact('model'));
    }

    public function update(Request $request, $id)
    {
        $model = app(config('sap.models.page'))->query()->findOrFail($id);

        $request->validate([
            'brand_id'     => 'required',
            'locale'       => 'required',
            'name'         => 'required',
            'slug'         => 'required',
            "published_at" => "required",
            "expired_at"   => "required",
            "status"       => "required",
        ]);

        $request->merge([
            'updated_by' => auth()->id(),
        ]);

        $model->update($request->all());

        activity('Updated Page: ' . $model->id, $request->all(), $model);

        Cache::flush();

        return response()->json([
            'status'   => 'success',
            'flash'    => 'Page Updated.',
            'reload'   => false,
            'relist'   => false,
            'redirect' => route('page.edit', [$model->id]),
            // 'redirect' => route('page.show', [$model->id]),
        ]);
    }

    public function destroy($id)
    {
        $model = app(config('sap.models.page'))->query()->findOrFail($id);
        $model->delete();

        activity('Deleted Page: ' . $model->id, [], $model);

        Cache::flush();

        return response()->json([
            'status'   => 'success',
            'flash'    => 'Page Deleted.',
            'reload'   => false,
            'relist'   => true,
            'redirect' => false,
        ]);
    }

    public function templates($brand_id)
    {
        $templates = [];
        if ($brand_id != '') {
            $model = app(config('sap.models.brand'))->query()->findOrFail($brand_id);
            if ($model) {
                foreach (File::files(base_path('brand/'.strtolower($model->name).'/layouts')) as $file) {
                    $name = str_replace('.blade.php', '', $file->getBasename());
                    $templates['layouts.'.$name] = $file->getBasename();
                }
            }
        }
        return response()->json($templates);
    }
}
