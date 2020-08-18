<?php
namespace Wikichua\SAP\Http\Observers;

use Elasticsearch\Client;

class ElasticSearchObserver
{
    private $elasticsearch;
    protected $models_list;

    public function __construct(Client $elasticsearch)
    {
        $this->elasticsearch = $elasticsearch;

        $this->models_list = esModelsList();
    }

    public function saved($model)
    {
        if (in_array(get_class($model), $this->models_list) && ($model->getEsFields() != null)) {
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
        if (in_array(get_class($model), $this->models_list) && ($model->getEsFields() != null)) {
            $this->elasticsearch->delete([
                'index' => $model->getSearchIndex(),
                'type' => $model->getSearchType(),
                'id' => $model->getKey(),
            ]);
        }
    }
}
