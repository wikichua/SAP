<?php

namespace Wikichua\SAP\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CarouselController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth_admin', 'can:Access Admin Panel']);
        $this->middleware('intend_url')->only(['index', 'read']);
        $this->middleware('can:Create Carousels')->only(['create', 'store']);
        $this->middleware('can:Read Carousels')->only(['index', 'read']);
        $this->middleware('can:Update Carousels')->only(['edit', 'update']);
        $this->middleware('can:Delete Carousels')->only('destroy');

        \Breadcrumbs::for('home', function ($trail) {
            $trail->push('Carousel Listing', route('carousel.list'));
        });
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
        \Breadcrumbs::for('breadcrumb', function ($trail) {
            $trail->parent('home');
            $trail->push('Create Carousel');
        });
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

        sendAlert([
            'brand_id' => $request->input('brand_id', 0),
            'link' => $model->readUrl,
            'message' => 'New Carousel Added. ('.$model->slug.')',
            'sender_id' => auth()->id(),
            'receiver_id' => permissionUserIds('Read Carousels', $request->input('brand_id', 0)),
            'icon' => $model->menu_icon
        ]);

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
        \Breadcrumbs::for('breadcrumb', function ($trail) {
            $trail->parent('home');
            $trail->push('Show Carousel');
        });
        $model = app(config('sap.models.carousel'))->query()->findOrFail($id);
        return view('sap::admin.carousel.show', compact('model'));
    }

    public function edit(Request $request, $id)
    {
        \Breadcrumbs::for('breadcrumb', function ($trail) {
            $trail->parent('home');
            $trail->push('Edit Carousel');
        });
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

        sendAlert([
            'brand_id' => $request->input('brand_id', 0),
            'link' => $model->readUrl,
            'message' => 'Carousel Updated. ('.$model->slug.')',
            'sender_id' => auth()->id(),
            'receiver_id' => permissionUserIds('Read Carousels', $request->input('brand_id', 0)),
            'icon' => $model->menu_icon
        ]);

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
        sendAlert([
            'brand_id' => $request->input('brand_id', 0),
            'link' => null,
            'message' => 'Carousel Deleted. ('.$model->slug.')',
            'sender_id' => auth()->id(),
            'receiver_id' => permissionUserIds('Read Carousels', $request->input('brand_id', 0)),
            'icon' => $model->menu_icon
        ]);
        $model->delete();

        return response()->json([
            'status' => 'success',
            'flash' => 'Carousel Deleted.',
            'reload' => false,
            'relist' => true,
            'redirect' => false,
        ]);
    }

    public function orderable(Request $request, $orderable = '', $brand_id = '')
    {
        if ($request->ajax()) {
            $models = app(config('sap.models.carousel'))->query()
                ->checkBrand()->orderBy('seq');
            if ($orderable != '') {
                $models->where('slug', $orderable);
            }
            if ($brand_id != '') {
                $models->where('brand_id', $brand_id);
            }
            $paginated['data'] = $models->take(100)->get();
            return compact('paginated');
        }
        \Breadcrumbs::for('breadcrumb', function ($trail) {
            $trail->parent('home');
            $trail->push('Ordering Carousel Position');
        });
        $getUrl = route('carousel.orderable', [$orderable, $brand_id]);
        $actUrl = route('carousel.orderableUpdate', [$orderable, $brand_id]);
        $html = [
            ['title' => 'ID', 'data' => 'id'],
            ['title' => 'Slug', 'data' => 'slug'],
            ['title' => 'Image Url', 'data' => 'image_url'],
        ];
        return view('sap::admin.carousel.orderable', compact('html', 'getUrl', 'actUrl'));
    }

    public function orderableUpdate(Request $request, $orderable = '', $brand_id = '')
    {
        $newRow = $request->get('newRow');
        $models = app(config('sap.models.carousel'))->query()->select('id')
            ->checkBrand()->orderByRaw('FIELD(id,'.$newRow.')');
        if ($orderable != '') {
            $models->where('slug', $orderable);
        }
        if ($brand_id != '') {
            $models->where('brand_id', $brand_id);
        }
        $models = $models->whereIn('id', explode(',', $newRow))->take(100)->get();
        foreach ($models as $seq => $model) {
            $model->seq = $seq+1;
            $model->saveQuietly();
        }

        activity('Reordered Carousel: ' . $newRow, $models->pluck('seq', 'id'), $model);
        sendAlert([
            'brand_id' => $brand_id,
            'link' => $model->readUrl,
            'message' => 'Carousel Position Reordered. ('.$model->slug.')',
            'sender_id' => auth()->id(),
            'receiver_id' => permissionUserIds('Read Carousels', $brand_id),
            'icon' => $model->menu_icon
        ]);
        return response()->json([
            'status' => 'success',
            'flash' => 'Carousel Reordered.',
            'reload' => false,
            'relist' => true,
            'redirect' => false,
        ]);
    }
}
