<?php

namespace Wikichua\SAP\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GlobalSearchController extends Controller
{
    public function __construct()
    {
    }

    public function index(Request $request)
    {
        $request->validate([
            'q' => 'required',
        ]);
        $queryStr = $request->input('q');
        $items = $this->search($queryStr);
        return view('sap::admin.dashboard.globalsearch')->with(compact('items'));
    }

    private function search(string $queryStr = '')
    {
        $models = getModelsList();
        $models = ['\App\User','\Wikichua\SAP\Models\Permission',];
        if (count($models)) {
            foreach ($models as $model) {
                $name = basename(str_replace('\\', '/', $model));
                $model = app($model);
                if (isset($model->searchableFields) && count($model->searchableFields)) {
                    $searchableFields = $model->searchableFields;
                    $items[$name] = $model->where(function ($q) use ($queryStr, $searchableFields) {
                        foreach ($searchableFields as $field) {
                            $q->orWhere($field, 'like', "%{$queryStr}%");
                        }
                    })->take(10)->get();
                }
            }
        }
        return $items;
    }
}
