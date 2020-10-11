<?php

namespace Wikichua\SAP\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SapES extends Command
{
    protected $signature = 'sap:es {--info}';
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

        if (true == $this->option('info')) {
            $result = shell_exec("curl localhost:9200/_cat/indices?v");
            $this->line($result);
        } else {
            $this->import();
        }
    }
    protected function import()
    {
        $this->info('Clean up indexes data stored in Elastic Search');
        shell_exec("curl -XDELETE 'localhost:9200/*_index_*'");

        $this->info('Importing to Elastic Search');
        $models = getModelsList();
        $bar = $this->output->createProgressBar(count($models));
        $bar->start();
        foreach ($models as $model) {
            \Artisan::call('scout:import', [
                'searchable' => $model
            ]);
            $bar->advance();
        }
        $bar->finish();
        $this->info("\nImporting Completed");
    }
}
