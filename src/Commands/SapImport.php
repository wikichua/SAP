<?php

namespace Wikichua\SAP\Commands;

use Illuminate\Console\Command;

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
        foreach (getModelsList() as $model) {
            \Artisan::call('scout:import', [
                'searchable' => $model
            ]);
            $this->output->write(\Artisan::output());
        }
    }
}
