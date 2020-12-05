<?php
namespace Wikichua\SAP\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use ZipArchive;

class SapExport extends Command
{
    protected $signature = 'sap:export {model} {export_path?} {--brand=} {--force}';
    protected $description = 'Export module into zip archive';

    public function __construct(Filesystem $file)
    {
        parent::__construct();
        $this->file = $file;
        $this->stub_path = config('sap.stub_path');
    }
    public function handle()
    {
        $this->brand = $this->option('brand')? \Str::studly($this->option('brand')):null;
        if ($this->brand) {
            $brand = app(config('sap.models.brand'))->query()->where('name', strtolower($this->brand))->first();
            if (!$brand) {
                $this->error('Brand not found: <info>'.$this->brand.'</info>');
                return '';
            }
            $this->model = $this->brand.(str_replace($this->brand, '', $this->argument('model')));
            $this->setModelString();
            $this->brand_path = base_path('brand/'.$this->brand);
            $this->config_file = $this->brand_path.'/config/sap/'.$this->model.'Config.php';
            $this->controller_admin_file = $this->brand_path.'/Controllers/Admin/'.$this->model.'Controller.php';
            $this->controller_api_file = $this->brand_path.'/Controllers/Api/'.$this->model.'Controller.php';
            $this->model_file = $this->brand_path.'/Models/'.$this->model.'.php';
            $this->resource_files = $this->brand_path.'/resources/views/admin'.$this->model_variable;
            $this->web_route_file = $this->brand_path.'/routes/sap/'.$this->model_variable.'Routes.php';
            $this->api_route_file = $this->brand_path.'/routes/sap/api/'.$this->model_variable.'Routes.php';
            $db_file = "sap{$this->model}Table.php";
            $this->db_path = base_path('brand/'.$this->brand.'/database/migrations');
        } else {
            $this->model = $this->argument('model');
            $this->setModelString();
            $this->config_file = config_path('sap/'.$this->model.'Config.php');
            $this->controller_admin_file = app_path('Http/Controllers/Admin/'.$this->model.'Controller.php');
            $this->controller_api_file = app_path('Http/Controllers/Api/'.$this->model.'Controller.php');
            $this->model_file = app_path('Models/'.$this->model.'.php');
            $this->resource_files = resource_path('views/admin/'.$this->model_variable);
            $this->web_route_file = base_path('routes/sap/'.$this->model_variable.'Routes.php');
            $this->api_route_file = base_path('routes/sap/api/'.$this->model_variable.'Routes.php');
            $db_file = "sap{$this->model}Table.php";
            $this->db_path = database_path('migrations');
        }

        foreach ($this->file->files($this->db_path) as $file) {
            if (str_contains($file->getPathname(), $db_file)) {
                $this->migration_file = $file->getPathname();
            }
        }
        $this->export();
        $this->zip();
    }
    private function setModelString()
    {
        $this->model_string = trim(preg_replace('/(?!^)[A-Z]{2,}(?=[A-Z][a-z])|[A-Z][a-z]/', ' $0', $this->model));
        $this->model_strings = str_plural($this->model_string);
        $this->model_variable = strtolower(str_replace(' ', '_', $this->model_string));
        $this->model_variables = strtolower(str_replace(' ', '_', $this->model_strings));
    }
    private function export()
    {
        if ($this->argument('export_path')) {
            $this->export_path = $this->argument('export_path').'/'.$this->model;
        } else {
            $this->export_path = storage_path('export/'.$this->model);
        }
        $this->config_path = $this->export_path.'/config/sap';
        $this->model_path = $this->export_path.'/Models';
        $this->controller_admin_path = $this->export_path.'/Controllers/Admin';
        $this->controller_api_path = $this->export_path.'/Controllers/Api';
        $this->resources_path = $this->export_path.'/resources/views/admin/'.$this->model_variable;
        $this->web_route_path = $this->export_path.'/routes/sap';
        $this->api_route_path = $this->export_path.'/routes/sap/api';
        $this->migration_path = $this->export_path.'/database/migrations';
        $this->file->ensureDirectoryExists($this->config_path);
        $this->file->ensureDirectoryExists($this->model_path);
        $this->file->ensureDirectoryExists($this->controller_admin_path);
        $this->file->ensureDirectoryExists($this->controller_api_path);
        $this->file->ensureDirectoryExists($this->resources_path);
        $this->file->ensureDirectoryExists($this->web_route_path);
        $this->file->ensureDirectoryExists($this->api_route_path);
        $this->file->ensureDirectoryExists($this->migration_path);

        $this->file->copy($this->config_file, $this->config_path.'/'.basename($this->config_file));
        $this->file->copy($this->model_file, $this->model_path.'/'.basename($this->model_file));
        $this->file->copy($this->controller_admin_file, $this->controller_admin_path.'/'.basename($this->controller_admin_file));
        $this->file->copy($this->controller_api_file, $this->controller_api_path.'/'.basename($this->controller_api_file));
        $this->file->copy($this->web_route_file, $this->web_route_path.'/'.basename($this->web_route_file));
        $this->file->copy($this->api_route_file, $this->api_route_path.'/'.basename($this->api_route_file));
        $this->file->copy($this->migration_file, $this->migration_path.'/'.basename($this->migration_file));
        $this->file->copyDirectory($this->resource_files, $this->resources_path);
    }
    private function zip()
    {
        $zip = new ZipArchive();
        $ret = $zip->open($this->export_path.'.zip', ZipArchive::CREATE);
        if ($ret !== true) {
            printf('Failed with code %d', $ret);
        } else {
            foreach ($this->file->allFiles($this->export_path) as $file) {
                $zip->addFile($file->getRealPath(), $file->getRelativePathname());
            }
            $zip->close();
            $this->file->deleteDirectory($this->export_path);
        }
    }
}
