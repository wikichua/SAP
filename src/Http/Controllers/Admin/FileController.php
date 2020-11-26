<?php

namespace Wikichua\SAP\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth_admin', 'can:Access Admin Panel']);
        $this->middleware('intend_url')->only(['index', 'read', 'preview']);
        $this->middleware('can:Create Files')->only(['create', 'store']);
        $this->middleware('can:Read Files')->only(['index', 'read', 'preview']);
        $this->middleware('can:Update Files')->only(['edit', 'update']);
        $this->middleware('can:Delete Files')->only('delete');
    }

    public function index(Request $request, $path = '')
    {
        if ($request->ajax()) {
            $filters = json_decode($request->get('filters'), 1);
            $path = '';
            if (isset($filters['path'])) {
                $path = str_replace(':', '/', $filters['path']);
            }
            $files = [];
            foreach (Storage::disk('public')->files($path) as $file) {
                $filepath = str_replace('/', ':', $file);
                $files[] = [
                    'path' => $file,
                    'filename' => basename($file),
                    'actionsView' => view('sap::admin.file.actions', compact('filepath'))->render(),
                ];
            }
            $paginated = $this->paginate($files, $request->get('take', 25));
            if ($request->get('filters', '') != '') {
                $paginated->appends(['filters' => $request->get('filters', '')]);
            }
            $links      = $paginated->onEachSide(5)->links()->render();
            $currentUrl = $request->fullUrl();
            return compact('paginated', 'links', 'currentUrl');
        }
        $getUrl = route('file.list');
        $html   = [
            ['title' => 'Path', 'data' => 'path', 'sortable' => false],
            ['title' => 'File', 'data' => 'filename', 'sortable' => false],
            ['title' => '', 'data' => 'actionsView'],
        ];
        return view('sap::admin.file.index', compact('html', 'getUrl', 'path'));
    }

    public function directories(Request $request)
    {
        $directories = [];
        $path = str_replace(':', '/', $request->input('path'));
        $pathArray = explode('/', $path);
        $pathCount = count($pathArray);
        // dd($pathCount);
        if ($pathCount >= 1) {
            $currentDirectory = $pathArray[$pathCount - 1] != ''? $pathArray[$pathCount - 1]:'Top';
            $directories[] = [
                'path' => str_replace('/', ':', $path),
                'label' => $currentDirectory,
                'title' => 'Current directory <strong>'.$currentDirectory.'</strong>',
            ];
            if ($currentDirectory != 'Top') {
                unset($pathArray[$pathCount - 1]);
                $directories[] = [
                    'path' => implode(':', $pathArray),
                    'label' => count($pathArray) == 1? 'Top':last($pathArray),
                    'title' => 'Back',
                ];
            }
        }
        foreach (Storage::disk('public')->directories($path) as $directory) {
            $directories[] = [
                'path' => str_replace('/', ':', $directory),
                'label' => basename($directory),
                'title' => basename($directory),
            ];
        }
        return $directories;
    }

    public function upload(Request $request, $path = '')
    {
        return view('sap::admin.file.upload', compact('path'));
    }

    public function store(Request $request, $path = '')
    {
        $request->validate([
            'files'     => 'required',
        ]);
        $path = str_replace(':', '/', $path);
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $key => $file) {
                $name = $request->file('files.'.$key)->getClientOriginalName();
                $extension = $request->file('files.'.$key)->extension();
                Storage::disk('public')->putFileAs($path, $request->file('files.'.$key), $name.'.'.$extension);
            }
        }
        $path = str_replace('/', ':', $path);
        return response()->json([
            'status'   => 'success',
            'flash'    => 'File Uploaded.',
            'reload'   => false,
            'relist'   => false,
            'redirect' => route('file.list', [$path]),
            // 'redirect' => route('page.show', [$model->id]),
        ]);
    }

    /*public function show($id)
    {
        $model = app(config('sap.models.page'))->query()->findOrFail($id);
        return view('sap::admin.page.show', compact('model'));
    }

    public function preview($id)
    {
        $model = app(config('sap.models.page'))->query()->findOrFail($id);
        $brandName = strtolower($model->brand->name);
        \View::addNamespace($brandName, base_path('brand/'.$brandName));
        return view($brandName.'::pages.page', compact('model'));
    }*/

    public function duplicate($path = '')
    {
        $listpath = str_replace(':', '/', $path);
        $pathArray = explode('/', $listpath);
        unset($pathArray[count($pathArray) - 1]);
        $listpath = implode(':', $pathArray);
        return view('sap::admin.file.duplicate', compact('path', 'listpath'));
    }

    public function copied(Request $request, $path = '')
    {
        $path = str_replace(':', '/', $path);

        $request->validate([
            'name'     => 'required',
        ]);
        $pathArray = explode('/', $path);
        unset($pathArray[count($pathArray) - 1]);
        $pathArray[] = $request->get('name');
        $newPath = implode('/', $pathArray);
        Storage::disk('public')->copy($path, $newPath);
        $newPath = str_replace('/', ':', $newPath);
        return response()->json([
            'status'   => 'success',
            'flash'    => 'File Duplicated.',
            'reload'   => false,
            'relist'   => false,
            'redirect' => route('file.duplicate', [$newPath]),
            // 'redirect' => route('page.show', [$model->id]),
        ]);
    }

    public function rename(Request $request, $path = '')
    {
        $listpath = str_replace(':', '/', $path);
        $pathArray = explode('/', $listpath);
        unset($pathArray[count($pathArray) - 1]);
        $listpath = implode(':', $pathArray);
        return view('sap::admin.file.rename', compact('path', 'listpath'));
    }

    public function update(Request $request, $path = '')
    {
        $path = str_replace(':', '/', $path);

        $request->validate([
            'name'     => 'required',
        ]);
        $pathArray = explode('/', $path);
        unset($pathArray[count($pathArray) - 1]);
        $pathArray[] = $request->get('name');
        $newPath = implode('/', $pathArray);
        Storage::disk('public')->move($path, $newPath);
        $newPath = str_replace('/', ':', $newPath);
        return response()->json([
            'status'   => 'success',
            'flash'    => 'File Renamed.',
            'reload'   => false,
            'relist'   => false,
            'redirect' => route('file.rename', [$newPath]),
            // 'redirect' => route('page.show', [$model->id]),
        ]);
    }

    public function destroy($path)
    {
        Storage::disk('public')->delete(str_replace(':', '/', $path));
        return response()->json([
            'status'   => 'success',
            'flash'    => 'File Deleted.',
            'reload'   => false,
            'relist'   => true,
            'redirect' => false,
        ]);
    }

    protected function paginate($items, $perPage = 15, $page = null)
    {
        $pageName = 'page';
        $page     = $page ?: (Paginator::resolveCurrentPage($pageName) ?: 1);
        $items    = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator(
            $items->forPage($page, $perPage)->values(),
            $items->count(),
            $perPage,
            $page,
            [
                'path'     => Paginator::resolveCurrentPath(),
                'pageName' => $pageName,
            ]
        );
    }
}
