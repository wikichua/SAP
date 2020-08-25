<?php

namespace Wikichua\SAP\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SapImport extends Command
{
    protected $signature = 'sap:import';
    protected $description = 'Reimport Models to Elastic Search';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $env = base_path('.env');
        if (env('SCOUT_DRIVER', '') == '' && File::exists($env) && File::isWritable($env)) {
            $str[] = 'SCOUT_DRIVER=Matchish\ScoutElasticSearch\Engines\ElasticSearchEngine';
            if (env('ELASTICSEARCH_HOST', '') == '') {
                $str[] = 'ELASTICSEARCH_HOST=localhost:9200';
            }
            if (strpos(File::get($env), 'SCOUT_DRIVER')) {
                $content = str_replace('SCOUT_DRIVER=', implode(PHP_EOL, $str), File::get($env));
                File::replace($env, $content);
            } else {
                File::append($env, PHP_EOL.implode(PHP_EOL, $str));
            }
        }

        foreach (getModelsList() as $model) {
            \Artisan::call('scout:import', [
                'searchable' => $model
            ]);
            $this->output->write(\Artisan::output());
        }
    }
}
