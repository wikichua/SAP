<?php

namespace Wikichua\SAP\Commands;

use Illuminate\Console\Command;
use Elasticsearch\Client;

class SapES extends Command
{
    protected $signature = 'sap:es';
    protected $description = 'Reindex models';
    public function __construct(Client $elasticsearch)
    {
        parent::__construct();
        $this->elasticsearch = $elasticsearch;
    }
    public function handle()
    {
        $models = config('sap.elasticsearch_models');
        if (count($models)) {
            foreach ($models as $name => $model) {
                $this->info('Indexing all '.$name.'. This might take a while...');
                $this->es($model);
            }
        }
        $this->info("\nDone!");
    }

    public function es($model)
    {
        foreach (app($model)->query()->cursor() as $data) {
            $this->elasticsearch->index([
                'index' => $data->getSearchIndex(),
                'type' => $data->getSearchType(),
                'id' => $data->getKey(),
                'body' => $data->toSearchArray(),
            ]);
            $this->output->write('.');
        }
    }
}
