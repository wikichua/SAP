<?php

namespace Wikichua\SAP\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;

class ComponentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth_admin', 'can:Access Admin Panel']);
        $this->middleware('intend_url')->only(['index', 'read']);
        $this->middleware('can:Read Components')->only(['index', 'read']);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $models = app(config('sap.models.component'))->query()
                ->with('brand')
                ->checkBrand()
                ->filter($request->get('filters', ''))
                ->sorting($request->get('sort', ''), $request->get('direction', ''));
            $paginated = $models->paginate($request->get('take', 25));
            foreach ($paginated as $model) {
                $model->actionsView = view('sap::admin.component.actions', compact('model'))->render();
                $model->usage = "&lt;x-$model->brand_name::".\Str::kebab($model->name).">&lt;/x-$model->brand_name::".\Str::kebab($model->name).">";
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
        $getUrl = route('component.list');
        $html = [
            ['title' => 'Name', 'data' => 'name', 'sortable' => true, 'filterable' => true],
            ['title' => 'Brand', 'data' => 'brand.name', 'sortable' => false, 'filterable' => false],
            ['title' => 'Usage Example', 'data' => 'usage', 'sortable' => false, 'filterable' => false],
            ['title' => '', 'data' => 'actionsView'],
        ];
        return view('sap::admin.component.index', compact('html', 'getUrl'));
    }

    public function show($id)
    {
        $model = app(config('sap.models.component'))->query()->findOrFail($id);
        return view('sap::admin.component.show', compact('model'));
    }

    public function try(Request $request, $id)
    {
        $model = app(config('sap.models.component'))->query()->findOrFail($id);
        $request->validate([
            "code" => "required",
        ]);
        if ($model->brand_id != 0) {
            View::addNamespace(strtolower($model->brand_name), base_path('brand/'.$model->brand->name.'/resources/views'));
            Blade::componentNamespace('\\Brand\\'.$model->brand->name.'\\Components', strtolower($model->brand_name));
        }
        return viewRenderer($request->input('code'));
    }
}
