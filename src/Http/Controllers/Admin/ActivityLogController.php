<?php

namespace Wikichua\SAP\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $fields = collect($request->get('fields'))->where('filterable', true)->pluck('key')->all();
        $filter = $request->get('filter', '');
        $sortBy = $request->get('sortBy', '');
        $sortDesc = $request->get('sortDesc', '');
        $activitylogs = app(config('vam.models.activity_log'))->query()->with('user')->filter($filter, $fields)->sorting($sortBy, $sortDesc)->paginate($request->get('size', 20));
        return response()->json($activitylogs);
    }

    public function show($id)
    {
        $activitylog = app(config('vam.models.activity_log'))->query()->with(['user'])->where('id', $id)->first();
        return response()->json($activitylog);
    }
}
