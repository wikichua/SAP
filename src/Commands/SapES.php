<?php

namespace Wikichua\SAP\Commands;

use Illuminate\Console\Command;
use Elasticsearch\Client;

class SapES extends Command
{
    protected $signature = 'sap:es {--clean}';
    protected $description = 'Reindex models';
    public function __construct(Client $elasticsearch)
    {
        parent::__construct();
        $this->elasticsearch = $elasticsearch;
        \Cache::forget('esModelsList');
    }
    public function handle()
    {
        $models = esModelsList();
        if (count($models)) {
            foreach ($models as $model) {
                if (app($model)->getEsFields() != null) {
                    $this->info('Indexing all '.$model.'. This might take a while...');
                    $this->es($model);
                } else {
                    $this->error($model . ': EsFields went wrong!');
                }
            }
        }
        $this->info("\nDone!");
    }

    public function es($model)
    {
        foreach (app($model)->query()->cursor() as $data) {
            if ($this->option('clean')) {
                $this->elasticsearch->indices()->delete([
                    'index' => app($model)->getSearchIndex(),
                ]);
            } else {
                $this->elasticsearch->index([
                    'index' => $data->getSearchIndex(),
                    'type' => $data->getSearchType(),
                    'id' => $data->getKey(),
                    'body' => $data->toSearchArray(),
                ]);
            }
            $this->output->write('.');
        }
        $this->output->write("\n");
    }
}
