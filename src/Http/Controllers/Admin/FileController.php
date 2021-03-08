<?php

namespace Wikichua\SAP\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth_admin', 'can:Access Admin Panel']);
        $this->middleware('intend_url')->only(['index', 'read', 'preview']);
        $this->middleware('can:Upload Files')->only(['upload']);
        $this->middleware('can:Rename Files')->only(['rename']);
        $this->middleware('can:Delete Files')->only('destroy');
        $this->middleware('can:Copy Files')->only('duplicate');

        $this->middleware('can:Create Folders')->only(['make']);
        $this->middleware('can:Rename Folders')->only(['change']);
        $this->middleware('can:Delete Folders')->only('remove');
        $this->middleware('can:Copy Folders')->only('clone');

        // $brand_id = 1;
        $brand_id = auth()->user()->brand_id ?? 0;
        if ($brand_id) {
            $this->storagePath = storage_path('app/public/brand/'.$brand_id);
            File::ensureDirectoryExists($this->storagePath);
        } else {
            $this->storagePath = storage_path('app/public');
        }
        \Breadcrumbs::for('home', function ($trail) {
            $trail->push('Files Listing', route('file.list'));
        });
    }

    public function index(Request $request, $path = '')
    {
        if ($request->ajax()) {
            $filters = json_decode($request->get('filters'), 1);
            $path = $this->storagePath;
            if (isset($filters['path'])) {
                $path .= '/'.str_replace(':', '/', $filters['path']);
            }
            $files = [];
            $storagePath = str_replace(storage_path('app'), '', $this->storagePath);
            foreach (File::files($path) as $file) {
                $filename = $file->getBasename();
                $temp_filepath = ltrim(str_replace($this->storagePath, '', $file->getPathname()), '/');
                $filepath = ltrim(str_replace('/', ':', $temp_filepath), '/');
                $files[] = [
                    'path' => '<a href="'.str_replace('public/', '', Storage::disk('public')->url($storagePath.'/'.$temp_filepath)).'" target="_blank">'.$temp_filepath.'</a>',
                    'last_modified' => \Carbon\Carbon::parse(Storage::lastModified($storagePath.'/'.$temp_filepath), auth()->user()->timezone)->toDateTimeString(),
                    'extension' => $file->getExtension(),
                    'size' => Storage::size($storagePath.'/'.$temp_filepath).' kb',
                    'actionsView' => view('sap::admin.file.actions', compact('filepath', 'filename'))->render(),
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
            ['title' => 'Type', 'data' => 'extension', 'sortable' => false],
            ['title' => 'Size', 'data' => 'size', 'sortable' => false],
            ['title' => 'Modified', 'data' => 'last_modified', 'sortable' => false],
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
        if ($pathCount >= 1) {
            $currentDirectory = $pathArray[$pathCount - 1] != ''? $pathArray[$pathCount - 1]:'Top';
            if ($currentDirectory != 'Top') {
                unset($pathArray[$pathCount - 1]);
                $data = [
                    'path' => implode(':', $pathArray),
                    'label' => count($pathArray) == 1? 'Top':last($pathArray),
                    'title' => 'Back',
                ];
                $directories[] = [
                    'view' => '<button data-href="'.$data['path'].'" class="list-group-item list-group-item-action goToDirectory" id="goToTopDirectory" data-title="'.$data['label'].'">'.$data['title'].'</button>',
                ];
            }

            $data = [
                'path' => str_replace('/', ':', $path),
                'label' => $currentDirectory,
                'title' => 'Current directory <strong>'.$currentDirectory.'</strong>',
                'dirname' => basename($path),
                'dom_id' => 'currentPathId'
            ];
            $directories[] = [
                'view' => view('sap::admin.file.directories', compact('data'))->render(),
            ];
        }
        foreach (File::directories($this->storagePath.'/'.$path) as $directory) {
            $directory = str_replace($this->storagePath.'/', '', $directory);
            $data = [
                'path' => str_replace('/', ':', $directory),
                'label' => basename($directory),
                'title' => basename($directory),
                'dirname' => basename($directory),
            ];
            $directories[] = [
                'view' => view('sap::admin.file.directories', compact('data'))->render(),
            ];
        }
        return $directories;
    }

    public function upload(Request $request, $path = '')
    {
        $request->validate([
            'files'     => 'required',
        ]);
        $storagePath = str_replace(storage_path('app'), '', $this->storagePath);
        if ($path != '') {
            $path = $storagePath.'/'.str_replace(':', '/', $path);
        } else {
            $path = $storagePath;
        }
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $key => $file) {
                $name = $request->file('files.'.$key)->getClientOriginalName();
                $upload_path = $request->file('files.'.$key)->storeAs($path, $name);
            }
        }
        return response()->json([
            'status'   => 'success',
            'flash'    => 'File Uploaded.',
            'reload'   => false,
            'relist'   => true,
            'redirect' => false,
            'currentUrl' => $request->fullUrl(),
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

    public function duplicate(Request $request, $path = '')
    {
        $path = $this->storagePath.'/'.str_replace(':', '/', $path);
        $request->validate([
            'name'     => 'required',
        ]);
        $pathArray = explode('/', $path);
        unset($pathArray[count($pathArray) - 1]);
        $pathArray[] = $request->get('name');
        $newPath = implode('/', $pathArray);
        File::copy($path, $newPath);
        $newPath = str_replace('/', ':', $newPath);
        return response()->json([
            'status'   => 'success',
            'flash'    => 'File Duplicated.',
            'reload'   => false,
            'relist'   => true,
            'redirect' => false,
            'currentUrl' => $request->fullUrl(),
            // 'redirect' => route('page.show', [$model->id]),
        ]);
    }

    public function rename(Request $request, $path = '')
    {
        $path = $this->storagePath.'/'.str_replace(':', '/', $path);
        $request->validate([
            'name'     => 'required',
        ]);
        $pathArray = explode('/', $path);
        unset($pathArray[count($pathArray) - 1]);
        $pathArray[] = $request->get('name');
        $newPath = implode('/', $pathArray);
        File::move($path, $newPath);
        $newPath = str_replace('/', ':', $newPath);
        return response()->json([
            'status'   => 'success',
            'flash'    => 'File Renamed.',
            'reload'   => false,
            'relist'   => true,
            'redirect' => false,
            'currentUrl' => $request->fullUrl(),
            // 'redirect' => route('page.show', [$model->id]),
        ]);
    }

    public function change(Request $request, $path = '')
    {
        $path = $this->storagePath.'/'.str_replace(':', '/', $path);
        $request->validate([
            'name'     => 'required',
        ]);
        $pathArray = explode('/', $path);
        unset($pathArray[count($pathArray) - 1]);
        $pathArray[] = $request->get('name');
        $newPath = implode('/', $pathArray);
        File::move($path, $newPath);
        $newPath = str_replace('/', ':', $newPath);
        return response()->json([
            'status'   => 'success',
            'flash'    => 'Folder Renamed.',
            'reload'   => false,
            'relist'   => true,
            'redirect' => false,
        ]);
    }

    public function clone(Request $request, $path = '')
    {
        $path = $this->storagePath.'/'.str_replace(':', '/', $path);
        $request->validate([
            'name'     => 'required',
        ]);
        $pathArray = explode('/', $path);
        unset($pathArray[count($pathArray) - 1]);
        $pathArray[] = $request->get('name');
        $newPath = implode('/', $pathArray);
        File::copyDirectory($path, $newPath);
        $newPath = str_replace('/', ':', $newPath);
        return response()->json([
            'status'   => 'success',
            'flash'    => 'Folder Duplicated.',
            'reload'   => false,
            'relist'   => true,
            'redirect' => false,
        ]);
    }

    public function make(Request $request, $path = '')
    {
        $path = $this->storagePath.'/'.str_replace(':', '/', $path);
        $request->validate([
            'name'     => 'required',
        ]);
        $pathArray = explode('/', $path);
        $pathArray[] = $request->get('name');
        $newPath = implode('/', $pathArray);
        File::makeDirectory($newPath);
        return response()->json([
            'status'   => 'success',
            'flash'    => 'Folder Created.',
            'reload'   => false,
            'relist'   => true,
            'redirect' => false,
        ]);
    }

    public function destroy($path)
    {
        File::delete($this->storagePath.'/'.str_replace(':', '/', $path));
        return response()->json([
            'status'   => 'success',
            'flash'    => 'File Deleted.',
            'reload'   => false,
            'relist'   => true,
            'redirect' => false,
        ]);
    }

    public function remove($path)
    {
        $path = $this->storagePath.'/'.str_replace(':', '/', $path);
        File::cleanDirectory($path);
        File::deleteDirectory($path);
        return response()->json([
            'status'   => 'success',
            'flash'    => 'Folder Deleted.',
            'reload'   => false,
            'relist'   => true,
            'redirect' => false,
        ]);
    }

    protected function paginate($items, $perPage = 25, $page = null)
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
