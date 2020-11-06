<?php

namespace Wikichua\SAP\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SapIndex extends Command
{
    protected $signature = 'sap:index {chunk=1000}';
    protected $description = 'Indexing searchable';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $chunk = $this->argument('chunk');
        $this->info('Indexing to Searchable');
        $models = getModelsList();
        app(config('sap.models.searchable'))->truncate();
        $searchable = app(config('sap.models.searchable'))->query();
        foreach ($models as $model) {
            if (count(app($model)->toSearchableArray()) && $count = app($model)->count()) {
                $this->info("\n".$model);
                $bar = $this->output->createProgressBar($count);
                $bar->start();
                app($model)->query()->orderBy('id')->chunk($chunk, function ($results) use ($searchable, $bar) {
                    foreach ($results as $result) {
                        $searchable->create([
                            'model' => $result->searchableAs(),
                            'model_id' => $result->id,
                            'tags' => $result->toSearchableArray(),
                            'brand_id' => $result->brand_id ?? 0,
                        ]);
                        $bar->advance();
                    }
                });
                $bar->finish();
            }
        }
        $this->info("\nIndexing Completed");
    }
}
