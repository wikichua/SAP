<?php

namespace Wikichua\SAP\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CarouselController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth_admin', 'can:Access Admin Panel']);
        $this->middleware('intend_url')->only(['index', 'read']);
        $this->middleware('can:Create Carousels')->only(['create', 'store']);
        $this->middleware('can:Read Carousels')->only(['index', 'read']);
        $this->middleware('can:Update Carousels')->only(['edit', 'update']);
        $this->middleware('can:Delete Carousels')->only('delete');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $models = app(config('sap.models.carousel'))->query()
                ->with('brand')
                ->checkBrand()
                ->filter($request->get('filters', ''))
                ->sorting($request->get('sort', ''), $request->get('direction', ''));
            $paginated = $models->paginate($request->get('take', 25));
            foreach ($paginated as $model) {
                $model->actionsView = view('sap::admin.carousel.actions', compact('model'))->render();
                $model->image = '<img src="'.asset($model->image_url).'" style="max-height:50px;" />';
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
        $getUrl = route('carousel.list');
        $html = [
            ['title' => 'Brand', 'data' => 'brand.name', 'sortable' => false],
            ['title' => 'Slug', 'data' => 'slug', 'sortable' => true, 'filterable' => true],
            ['title' => 'Image', 'data' => 'image', 'sortable' => false, 'filterable' => false],
            ['title' => 'Tags', 'data' => 'tags', 'sortable' => false, 'filterable' => true],
            ['title' => 'Published Date', 'data' => 'published_at', 'sortable' => false, 'filterable' => true],
            ['title' => 'Expired Date', 'data' => 'expired_at', 'sortable' => false, 'filterable' => true],
            ['title' => '', 'data' => 'actionsView'],
        ];
        return view('sap::admin.carousel.index', compact('html', 'getUrl'));
    }

    public function create(Request $request)
    {
        return view('sap::admin.carousel.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            "slug" => "required|min:4",
            "brand_id" => "required",
            "image_url" => "required",
            "caption" => "",
            "seq" => "required",
            "tags" => "required",
            "published_at" => "required",
            "expired_at" => "required",
            "status" => "required",
        ]);
        if ($request->hasFile('image_url')) {
            $path = str_replace('public', 'storage', $request->file('image_url')->store('public/carousel/image_url'));
            unset($request['image_url']);
            $request->merge([
                'image_url' => $path,
            ]);
        }
        $request->merge([
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        $model = app(config('sap.models.carousel'))->query()->create($request->input());

        return response()->json([
            'status' => 'success',
            'flash' => 'Carousel Created.',
            'reload' => false,
            'relist' => false,
            'redirect' => route('carousel.list'),
        ]);
    }

    public function show($id)
    {
        $model = app(config('sap.models.carousel'))->query()->findOrFail($id);
        return view('sap::admin.carousel.show', compact('model'));
    }

    public function edit(Request $request, $id)
    {
        $model = app(config('sap.models.carousel'))->query()->findOrFail($id);
        return view('sap::admin.carousel.edit', compact('model'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            "slug" => "required|min:4",
            "brand_id" => "required",
            "image_url" => "required",
            "caption" => "",
            "seq" => "required",
            "tags" => "required",
            "published_at" => "required",
            "expired_at" => "required",
            "status" => "required",
        ]);
        if ($request->hasFile('image_url')) {
            $path = str_replace('public', 'storage', $request->file('image_url')->store('public/carousel/image_url'));
            unset($request['image_url']);
            $request->merge([
                'image_url' => $path,
            ]);
        }
        $request->merge([
            'updated_by' => auth()->id(),
        ]);

        $model = app(config('sap.models.carousel'))->query()->findOrFail($id);

        $model->update($request->input());

        return response()->json([
            'status' => 'success',
            'flash' => 'Carousel Updated.',
            'reload' => false,
            'relist' => false,
            'redirect' => route('carousel.edit', [$model->id]),
        ]);
    }

    public function destroy($id)
    {
        $model = app(config('sap.models.carousel'))->query()->findOrFail($id);

        $model->delete();

        return response()->json([
            'status' => 'success',
            'flash' => 'Carousel Deleted.',
            'reload' => false,
            'relist' => true,
            'redirect' => false,
        ]);
    }

    public function orderable(Request $request, $orderable = '')
    {
        if ($request->ajax()) {
            $models = app(config('sap.models.carousel'))->query()
                ->checkBrand()->orderBy('seq');
            if ($orderable != '') {
                $models->where('slug', $orderable);
            }
            $paginated['data'] = $models->take(100)->get();
            return compact('paginated');
        }
        $getUrl = route('carousel.orderable', $orderable);
        $actUrl = route('carousel.orderableUpdate', $orderable);
        $html = [
            ['title' => 'ID', 'data' => 'id'],
            ['title' => 'Slug', 'data' => 'slug'],
            ['title' => 'Image Url', 'data' => 'image_url'],
        ];
        return view('sap::admin.carousel.orderable', compact('html', 'getUrl', 'actUrl'));
    }

    public function orderableUpdate(Request $request, $orderable = '')
    {
        $newRow = $request->get('newRow');
        $models = app(config('sap.models.carousel'))->query()->select('id')
            ->checkBrand()->orderByRaw('FIELD(id,'.$newRow.')');
        if ($orderable != '') {
            $models->where('slug', $orderable);
        }
        $models = $models->whereIn('id', explode(',', $newRow))->take(100)->get();
        foreach ($models as $seq => $model) {
            $model->seq = $seq+1;
            $model->saveQuietly();
        }

        activity('Reordered Carousel: ' . $newRow, $models->pluck('seq', 'id'), $model);

        return response()->json([
            'status' => 'success',
            'flash' => 'Carousel Reordered.',
            'reload' => false,
            'relist' => true,
            'redirect' => false,
        ]);
    }
}
