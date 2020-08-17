<?php

namespace Wikichua\SAP\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Elasticsearch\Client;
use Illuminate\Http\Request;

class ElasticSearchController extends Controller
{
    public function __construct(Client $elasticsearch)
    {
        $this->elasticsearch = $elasticsearch;
    }

    public function index(Request $request)
    {
        $request->validate([
            'q' => 'required',
        ]);
        $query = $request->input('q');
        $items = $this->searchOnElasticsearch($query);

        return $items;
    }

    private function searchOnElasticsearch(string $query = '')
    {
        $models = config('sap.elasticsearch_models');
        if (count($models)) {
            foreach ($models as $name => $model) {
                $model = app($model);
                $item = $this->elasticsearch->search([
                    'index' => $model->getSearchIndex(),
                    'type' => $model->getSearchType(),
                    'body' => [
                        'query' => [
                            'multi_match' => [
                                'fields' => isset($model->EsFields) && is_array($model->EsFields)? $model->EsFields:['name'],
                                'query' => $query,
                            ],
                        ],
                    ],
                ]);
                $items[$name] = $this->buildCollection($item, $model);
            }
        }
        return view('sap::admin.dashboard.elasticsearch')->with(compact('items'));
    }

    private function buildCollection(array $items, $model)
    {
        $ids = \Arr::pluck($items['hits']['hits'], '_id');
        return $model->query()->findMany($ids)
            ->sortBy(function ($model) use ($ids) {
                return array_search($model->getKey(), $ids);
            });
    }
}
