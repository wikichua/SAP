<?php

namespace Wikichua\SAP\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MailerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth_admin', 'can:Access Admin Panel']);
        $this->middleware('intend_url')->only(['index', 'read']);
        $this->middleware('can:Read Mailers')->only(['index', 'read']);
        $this->middleware('can:Update Mailers')->only(['edit', 'update']);
        $this->middleware('can:Delete Mailers')->only('destroy');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $models = app(config('sap.models.mailer'))->query()
                ->checkBrand()
                ->filter($request->get('filters', ''))
                ->sorting($request->get('sort', ''), $request->get('direction', ''));
            $paginated = $models->paginate($request->get('take', 25));
            foreach ($paginated as $model) {
                $model->actionsView = view('sap::admin.mailer.actions', compact('model'))->render();
                $model->parameters = app($model->mailable)->getVariables();
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
        $getUrl = route('mailer.list');
        $html = [
            ['title' => 'Mailable', 'data' => 'mailable', 'sortable' => false, 'filterable' => true],
            ['title' => 'Subject', 'data' => 'subject', 'sortable' => false, 'filterable' => true],
            ['title' => 'Available Params', 'data' => 'parameters', 'sortable' => false, 'filterable' => true],
            ['title' => 'Created Date', 'data' => 'created_at', 'sortable' => false, 'filterable' => true],
            ['title' => '', 'data' => 'actionsView'],
        ];
        return view('sap::admin.mailer.index', compact('html', 'getUrl'));
    }

    public function show($id)
    {
        $model = app(config('sap.models.mailer'))->query()->findOrFail($id);
        $preview = app($model->mailable);
        return view('sap::admin.mailer.show', compact('model'));
    }

    public function edit(Request $request, $id)
    {
        $model = app(config('sap.models.mailer'))->query()->findOrFail($id);
        return view('sap::admin.mailer.edit', compact('model'));
    }

    public function update(Request $request, $id)
    {
        $model = app(config('sap.models.mailer'))->query()->findOrFail($id);

        $request->validate([
            'subject' => "required",
            'html_template' => "required",
            'text_template' => "required",
        ]);

        $request->merge([
            'updated_by' => auth()->id(),
        ]);

        $model->update($request->all());
        sendAlert([
            'brand_id' => 0,
            'link' => $model->readUrl,
            'message' => 'Mailer Updated. ('.$model->name.')',
            'sender_id' => auth()->id(),
            'receiver_id' => permissionUserIds('Read Mailers'),
            'icon' => $model->menu_icon
        ]);

        return response()->json([
            'status' => 'success',
            'flash' => 'Mailer Updated.',
            'reload' => false,
            'relist' => false,
            'redirect' => route('mailer.edit', [$model->id]),
        ]);
    }

    public function destroy($id)
    {
        $model = app(config('sap.models.mailer'))->query()->findOrFail($id);
        sendAlert([
            'brand_id' => 0,
            'link' => null,
            'message' => 'Mailer Deleted. ('.$model->name.')',
            'sender_id' => auth()->id(),
            'receiver_id' => permissionUserIds('Read Mailers'),
            'icon' => $model->menu_icon
        ]);
        $model->delete();
        return response()->json([
            'status' => 'success',
            'flash' => 'Mailer Deleted.',
            'reload' => false,
            'relist' => true,
            'redirect' => false,
        ]);
    }

    public function preview(Request $request, $id)
    {
        $model = app(config('sap.models.mailer'))->query()->findOrFail($id);
        $params = app($model->mailable)->getVariables();
        if ($request->isMethod('post')) {
            return app($model->mailable)->preview();
        }
        return view('sap::admin.mailer.preview', compact('model', 'params'));
    }
}
