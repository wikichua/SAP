<?php
namespace Wikichua\SAP\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use ZipArchive;

class SapImport extends Command
{
    protected $signature = 'sap:import {path} {--brand=} {--deleteAfterUnzip=yes}';
    protected $description = 'Import module from zip archive';

    public function __construct(Filesystem $file)
    {
        parent::__construct();
        $this->file = $file;
    }
    public function handle()
    {
        if ($this->argument('path')) {
            $this->path = $this->argument('path');
        }
        $this->unzip();
        $this->checkConfig();
        $this->brand = $this->option('brand')? \Str::studly($this->option('brand')):$this->config['brand'];
        $this->setModelString();
        $this->import();
        if ($this->option('deleteAfterUnzip') == 'yes') {
            $this->file->deleteDirectory($this->import_path);
        }
    }
    private function checkConfig()
    {
        $this->config = json_decode($this->file->get($this->import_path.'/import.json'), 1);
        $this->model = $this->config['model'];
    }
    private function setModelString()
    {
        $this->base_path = base_path();
        $this->menu_file = resource_path('views/vendor/sap/components/admin-menu.blade.php');
        if ($this->config['brand'] != null) {
            if ($this->brand) {
                $brand = app(config('sap.models.brand'))->query()->where('name', strtolower($this->brand))->first();
                if (!$brand) {
                    $this->error('Brand not found: <info>'.$this->brand.'</info>');
                    exit;
                }
            } else {
                $this->error('Brand module found: <info>Please input your desire Brand Name with option --brand=</info>');
                exit;
            }
            $this->base_path = base_path('brand/'.$this->brand);
            $this->menu_file = $this->base_path.'/resources/views/admin/menu.blade.php';
            if ($this->brand) {
                $this->model = $this->brand.(str_replace($this->brand, '', $this->model));
                if ($this->config['brand'] != $this->brand) {
                    $this->model = str_replace($this->config['brand'], '', $this->model);
                }
            }
        }
        $this->model_string = trim(preg_replace('/(?!^)[A-Z]{2,}(?=[A-Z][a-z])|[A-Z][a-z]/', ' $0', $this->model));
        $this->model_strings = str_plural($this->model_string);
        $this->model_variable = strtolower(str_replace(' ', '_', $this->model_string));
        $this->model_variables = strtolower(str_replace(' ', '_', $this->model_strings));
        $this->permission_string = $this->model_string;

        $this->newModelStr = [
            'model' => $this->model,
            'model_string' => $this->model_string,
            'model_strings' => $this->model_strings,
            'model_variable' => $this->model_variable,
            'model_variables' => $this->model_variables,
            'permission_string' => $this->permission_string,
        ];
    }
    private function import()
    {
        $this->oldModelStr = array_merge(['model' => $this->config['model']], $this->config['str']);
        foreach ($this->config['files'] as $file) {
            $from_file = $this->import_path.$file;
            $to_file = $this->base_path.$file;
            $to_file = str_replace($this->oldModelStr, $this->newModelStr, $to_file);
            $dir = str_replace(basename($to_file), '', $to_file);
            $this->file->ensureDirectoryExists($dir);
            $this->file->copy($from_file, $to_file);
            $content = $this->file->get($to_file);
            if ($this->config['brand'] != $this->brand) {
                $content = str_replace($this->oldModelStr, $this->newModelStr, $content);
                $content = str_replace($this->config['brand'], $this->brand, $content);
            }
            $this->file->replace($to_file, $content);
        }
        $menu_content = $this->file->get($this->menu_file);
        $this->config['menu'] = str_replace($this->oldModelStr, $this->newModelStr, $this->config['menu']);
        if (strpos($menu_content, $this->config['menu']) === false) {
            $menu_content = str_replace('<!--DoNotRemoveMe-->', $this->config['menu']."\n".'<!--DoNotRemoveMe-->', $menu_content);
            $this->file->replace($this->menu_file, $menu_content);
        }
    }
    private function unzip()
    {
        if ($this->file->exists($this->path) && $this->file->isReadable($this->path)) {
            $zip = new ZipArchive();
            $ret = $zip->open($this->path);
            if ($ret !== true) {
                printf('Failed with code %d', $ret);
            } else {
                $this->import_path = rtrim($this->path, '.zip');
                $zip->extractTo($this->import_path);
                $zip->close();
            }
            return true;
        }
        $this->error('Zip File not readable: <info>'.$this->path.'</info>');
        exit;
    }
}
