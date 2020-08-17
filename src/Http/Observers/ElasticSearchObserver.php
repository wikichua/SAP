<?php
namespace Wikichua\SAP\Http\Observers;

use Elasticsearch\Client;

class ElasticSearchObserver
{
    private $elasticsearch;

    public function __construct(Client $elasticsearch)
    {
        $this->elasticsearch = $elasticsearch;
    }

    public function saved($model)
    {
        if (in_array(get_class($model), config('sap.elasticsearch_models'))) {
            $this->elasticsearch->index([
                'index' => $model->getSearchIndex(),
                'type' => $model->getSearchType(),
                'id' => $model->getKey(),
                'body' => $model->toSearchArray(),
            ]);
        }
    }

    public function deleted($model)
    {
        if (in_array(get_class($model), config('sap.elasticsearch_models'))) {
            $this->elasticsearch->delete([
                'index' => $model->getSearchIndex(),
                'type' => $model->getSearchType(),
                'id' => $model->getKey(),
            ]);
        }
    }
}
