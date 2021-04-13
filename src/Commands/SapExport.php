<?php

namespace Wikichua\SAP\Commands;

use ZipArchive;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class SapExport extends Command
{
    protected $signature = 'sap:export {model} {path?} {--brand=} {--deleteAfterZip=yes}';
    protected $description = 'Export module into zip archive';

    public function __construct(Filesystem $file)
    {
        parent::__construct();
        $this->file = $file;
    }

    public function handle()
    {
        $this->brand = $this->option('brand') ? \Str::studly($this->option('brand')) : null;
        $this->setModelString();
        if ($this->brand) {
            $this->setBrandProperties();
        } else {
            $this->setDefaultProperties();
        }
        foreach ($this->file->files($this->db_path) as $file) {
            if (str_contains($file->getPathname(), $this->db_file)) {
                $this->migration_file = $file->getPathname();
            }
        }
        foreach ($this->file->files($this->resource_path) as $file) {
            $this->resource_files[] = $file->getPathname();
        }
        $this->checkConfig();
        $this->export();
        $this->zip();
    }

    private function checkConfig()
    {
        if ($this->argument('path')) {
            $this->path = $this->argument('path').'/'.$this->model;
        } else {
            $this->path = storage_path('export/'.$this->model);
        }
        $this->file->ensureDirectoryExists($this->path);
        $this->export_json = $this->path.'_export.json';
        if (!$this->file->exists($this->export_json)) {
            $export_config = [
                'model' => $this->model,
                'brand' => $this->brand,
                'files' => [
                    $this->migration_file,
                    $this->config_file,
                    $this->controller_admin_file,
                    $this->controller_api_file,
                    $this->model_file,
                    $this->web_route_file,
                    $this->api_route_file,
                ],
                'menu' => $this->menu_content,
                'dependencies' => [],
            ];
            $export_config['files'] = array_merge($export_config['files'], $this->resource_files);
            $confContent = json_encode((object) $export_config, JSON_PRETTY_PRINT);
            $this->error('Config file not found: <info>'.$this->export_json.'</info>');
            $this->file->put($this->export_json, $confContent);
            $this->info($this->export_json.' has been created. Please adjust it accordingly then rerun this artisan.');
        }
    }

    private function setDefaultProperties()
    {
        $this->base_path = base_path();
        $this->config_file = config_path('sap/'.$this->model.'Config.php');
        $this->controller_admin_file = app_path('Http/Controllers/Admin/'.$this->model.'Controller.php');
        $this->controller_api_file = app_path('Http/Controllers/Api/'.$this->model.'Controller.php');
        $this->model_file = app_path('Models/'.$this->model.'.php');
        $this->resource_path = resource_path('views/admin/'.$this->model_variable);
        $this->web_route_file = base_path('routes/sap/'.$this->model_variable.'Routes.php');
        $this->api_route_file = base_path('routes/sap/api/'.$this->model_variable.'Routes.php');
        $this->db_file = "sap{$this->model}Table.php";
        $this->db_path = database_path('migrations');
        $menu = $this->file->get(resource_path('views/vendor/sap/components/admin-menu.blade.php'));
        preg_match('/^@can\(\'Read '.$this->permission_string.'\'\).+@endcan$/ms', $menu, $matches);
        $this->menu_content = $matches[0];
    }

    private function setBrandProperties()
    {
        $this->base_path = base_path('brand/'.$this->brand);
        $this->config_file = $this->base_path.'/config/sap/'.$this->model.'Config.php';
        $this->controller_admin_file = $this->base_path.'/Controllers/Admin/'.$this->model.'Controller.php';
        $this->controller_api_file = $this->base_path.'/Controllers/Api/'.$this->model.'Controller.php';
        $this->model_file = $this->base_path.'/Models/'.$this->model.'.php';
        $this->resource_path = $this->base_path.'/resources/views/admin/'.$this->model_variable;
        $this->web_route_file = $this->base_path.'/routes/sap/'.$this->model_variable.'Routes.php';
        $this->api_route_file = $this->base_path.'/routes/sap/api/'.$this->model_variable.'Routes.php';
        $this->db_file = "sap{$this->model}Table.php";
        $this->db_path = base_path('brand/'.$this->brand.'/database/migrations');
        $menu = $this->file->get($this->base_path.'/resources/views/admin/menu.blade.php');
        preg_match('/^@can\(\'Read '.$this->permission_string.'\'\).+@endcan$/ms', $menu, $matches);
        $this->menu_content = $matches[0];
    }

    private function setModelString()
    {
        $this->model = $this->argument('model');
        if ($this->brand) {
            $brand = app(config('sap.models.brand'))->query()->where('name', strtolower($this->brand))->first();
            if (!$brand) {
                $this->error('Brand not found: <info>'.$this->brand.'</info>');

                exit;
            }
            $this->model = $this->brand.(str_replace($this->brand, '', $this->model));
        }
        $this->model_string = trim(preg_replace('/(?!^)[A-Z]{2,}(?=[A-Z][a-z])|[A-Z][a-z]/', ' $0', $this->model));
        $this->model_strings = str_plural($this->model_string);
        $this->model_variable = strtolower(str_replace(' ', '_', $this->model_string));
        $this->model_variables = strtolower(str_replace(' ', '_', $this->model_strings));
        $this->permission_string = $this->model_string;
    }

    private function export()
    {
        $config = json_decode($this->file->get($this->export_json), 1);
        $files = [];
        foreach ($config['files'] as $file) {
            $files[] = str_replace($this->base_path, '', $file);
            $export_dir = $this->path.str_replace(basename($file), '', str_replace($this->base_path, '', $file));
            $this->file->ensureDirectoryExists($export_dir);
            $this->file->copy($file, $export_dir.'/'.basename($file));
        }
        $config['files'] = $files;
        $config['str'] = [
            'model_string' => $this->model_string,
            'model_strings' => $this->model_strings,
            'model_variable' => $this->model_variable,
            'model_variables' => $this->model_variables,
            'permission_string' => $this->permission_string,
        ];
        $this->import_json = $this->path.'/import.json';
        $confContent = json_encode((object) $config, JSON_PRETTY_PRINT);
        $this->file->put($this->import_json, $confContent);
    }

    private function zip()
    {
        $zip = new ZipArchive();
        $ret = $zip->open($this->path.'.zip', ZipArchive::CREATE);
        if (true !== $ret) {
            printf('Failed with code %d', $ret);
        } else {
            foreach ($this->file->allFiles($this->path) as $file) {
                $zip->addFile($file->getRealPath(), $file->getRelativePathname());
            }
            $zip->close();
            if ('yes' == $this->option('deleteAfterZip')) {
                $this->file->deleteDirectory($this->path);
            }
            $this->info($this->path.'.zip has been created. To import, please run php artisan sap:import '.$this->path.'.zip');
        }
    }
}
