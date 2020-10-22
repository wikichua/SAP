<?php

namespace Wikichua\SAP\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class SapConfig extends Command
{
    protected $signature = 'sap:config {model} {--brand=}';
    protected $description = 'Generate Config File Into Config Directory';
    public function __construct()
    {
        parent::__construct();
    }
    public function handle(Filesystem $files)
    {
        $this->brand = $this->option('brand')? $this->option('brand'):null;

        if ($this->brand) {
            $brand = app(config('sap.models.brand'))->query()->where('name', strtolower($this->brand))->first();
            if (!$brand) {
                $this->error('Brand not found: <info>'.$this->brand.'</info>');
                return '';
            }
            $config_path = base_path('brand/'.strtolower($this->brand).'/config/sap');
            $model = $this->brand.$this->argument('model');
        } else {
            $config_path = 'config/sap';
            $model = $this->argument('model');
        }

        if (!$files->exists($config_path)) {
            $files->makeDirectory($config_path, 0755, true);
        }
        $config_stub = config('sap.stub_path') . '/config.stub';
        if (!$files->exists($config_stub)) {
            $config_stub = __DIR__ . '/../../resources/stubs/config.stub';
        }
        $config_file = $config_path . '/' . $model . 'Config.php';
        $files->copy($config_stub, $config_file);
        $this->line('Config file created: <info>' . $config_file . '</info>');
    }
}
