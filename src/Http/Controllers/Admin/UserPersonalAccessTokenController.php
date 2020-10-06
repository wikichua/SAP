<?php

namespace Wikichua\SAP\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Sanctum\Sanctum;

class UserPersonalAccessTokenController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth_admin', 'can:Access Admin Panel']);
        $this->middleware('intend_url')->only(['index', 'read']);
        $this->middleware('can:Create Personal Access Token')->only(['create', 'store']);
        $this->middleware('can:Read Personal Access Token')->only(['index', 'read']);
        $this->middleware('can:Delete Personal Access Token')->only('delete');
    }

    public function index(Request $request, $user_id)
    {
        if ($request->ajax()) {
            $models = app(Sanctum::$personalAccessTokenModel)->query()
                ->checkBrand()
                ->where('tokenable_id', $user_id);
            $paginated = $models->paginate(25);
            $links = $paginated->onEachSide(5)->links()->render();
            $currentUrl = $request->fullUrl();
            return compact('paginated', 'links', 'currentUrl');
        }
        $getUrl = route('pat.list', [$user_id]);
        $html = [
            ['title' => 'Name', 'data' => 'name', 'sortable' => true],
            ['title' => 'Token', 'data' => 'plain_text_token', 'sortable' => true],
            ['title' => 'Abilities', 'data' => 'abilities', 'sortable' => true],
            ['title' => 'Last Actived', 'data' => 'last_used_at', 'sortable' => true],
        ];
        $user = app(config('sap.models.user'))->query()->find($user_id);
        return view('sap::admin.pat.index', compact('html', 'getUrl', 'user', 'user_id'));
    }

    public function create(Request $request, $user_id)
    {
        $user = app(config('sap.models.user'))->query()->find($user_id);
        return view('sap::admin.pat.create', compact('user', 'user_id'));
    }

    public function store(Request $request, $user_id)
    {
        $user = app(config('sap.models.user'))->query()->find($user_id);
        $permissions = $user->roles->contains('admin', true)? ['*']:$user->flatPermissions()->toArray();
        $tokenResult = $user->createToken($request->input('name', 'authToken'), $permissions)->plainTextToken;
        $tokenResult = explode('|', $tokenResult);
        $model = app(Sanctum::$personalAccessTokenModel)->query()
                ->find($tokenResult[0]);
        $model->plain_text_token = $tokenResult[1];
        $model->save();

        activity('Created Personal Access Token: ' . $model->id, $request->input(), $model);

        return response()->json([
            'status' => 'success',
            'flash' => 'Personal Access Token Created.',
            'reload' => false,
            'relist' => false,
            'redirect' => route('pat.list', [$user_id]),
        ]);
    }

    public function destroy($user_id, $id)
    {
        $model = app(Sanctum::$personalAccessTokenModel)->query()->findOrFail($id);
        $model->delete();

        activity('Deleted Personal Access Token: ' . $model->id, [], $model);

        return response()->json([
            'status' => 'success',
            'flash' => 'Personal Access Token Deleted.',
            'reload' => false,
            'relist' => true,
            'redirect' => false,
        ]);
    }
}
