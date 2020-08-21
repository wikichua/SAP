<?php

namespace Wikichua\SAP\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Matchish\ScoutElasticSearch\Mixed;

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
        $items = [];
        $searchable_models = [];
        // $models = ['\App\User','\Wikichua\SAP\Models\Permission',];
        if (count($models)) {
            foreach ($models as $model) {
                $name = basename(str_replace('\\', '/', $model));
                $model = app($model);
                if (isset($model->searchableFields) && count($model->searchableFields)) {
                    $searchable_models[] = $model->searchableAs();
                }
            }
            if (count($searchable_models)) {
                $items = Mixed::search($queryStr)->within(implode(',', $searchable_models))->get();
            }
        }
        return $items;
    }
}
