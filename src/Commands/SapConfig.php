<?php

namespace Wikichua\SAP\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class SapConfig extends Command
{
    protected $signature = 'sap:config {model}';
    protected $description = 'Generate Config File Into Config Directory';
    public function __construct()
    {
        parent::__construct();
    }
    public function handle(Filesystem $files)
    {
        $config_path = 'config/sap';
        if (!$files->exists($config_path)) {
            $files->makeDirectory($config_path, 0755, true);
        }
        $config_stub = config('sap.stub_path') . '/config.stub';
        if (!$files->exists($config_stub)) {
            $config_stub = __DIR__ . '/../../resources/stubs/config.stub';
        }
        $config_file = $config_path . '/' . $this->argument('model') . 'Config.php';
        $files->copy($config_stub, $config_file);
        $this->line('Config file created: <info>' . $config_file . '</info>');
    }
}
