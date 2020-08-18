<?php

namespace Wikichua\SAP\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class SapBrand extends Command
{
    protected $signature = 'sap:brand {brand} {--force}';
    protected $description = 'Make Up The BRAND';

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
        $this->stub_path = config('sap.stub_path').'/brand';
    }

    public function handle()
    {
        $this->brand = $this->argument('brand');
        $this->replaces['{%brand_name%}'] = $brand_name = $this->brand;
        $this->replaces['{%brand_string%}'] = $brand_string = strtolower($this->brand);
        $this->replaces['{%custom_controller_namespace%}'] = str_replace('Admin', 'Brand', config('sap.custom_controller_namespace'));
        $this->brand_path = $brand_path = resource_path('views/brand/'.$brand_string);
        if (!$this->files->exists($brand_path)) {
            $this->files->makeDirectory($brand_path, 0755, true);
        } else {
            $this->info('Brand <info>'.$this->brand.'</info> has already existed!');
            if (false == $this->option('force')) {
                return;
            }
            $this->info('So you\'ve decided to overwrite it!');
        }
        $this->assets();
        $this->route();
        $this->controller();
        $this->justCopy('layouts');
        $this->justCopy('pages');
        $this->package();
        $this->webpack();
        $this->others();
        $this->line('<info>If you are using valet...</info>');
        $this->line('<info>Run this...</info>');
        $this->line('<info>cd ./public</info>');
        $this->line('<info>valet link '.$this->brand.'</info>');
        $this->line('<info>valet secure</info>');
    }

    protected function assets()
    {
        $asset_stub = $this->stub_path.'/assets';
        $asset_dir = $this->brand_path.'/assets';
        $this->files->copyDirectory($asset_stub, $asset_dir);
        $this->line('Assets copied: <info>'.$asset_dir.'</info>');
    }

    protected function route()
    {
        $route_file = $this->brand_path.'/web.php';
        $route_stub = $this->stub_path.'/web.php.stub';
        if (!$this->files->exists($route_stub)) {
            $this->error('Web stub file not found: <info>'.$route_stub.'</info>');
            return;
        }
        $route_stub = $this->files->get($route_stub);
        $this->files->put($route_file, $this->replaceholder($route_stub));
        $this->line('Web file created: <info>'.$route_file.'</info>');
    }

    protected function controller()
    {
        $controller_stub = $this->stub_path.'/controller.stub';
        if (!$this->files->exists($controller_stub)) {
            $this->error('Controller stub file not found: <info>'.$controller_stub.'</info>');
            return;
        }
        $controller_dir = str_replace('Admin', 'Brand', config('sap.custom_controller_dir'));
        $controller_file = app_path($controller_dir.'/'.$this->brand.'Controller.php');
        $controller_stub = $this->files->get($controller_stub);
        $this->files->put($controller_file, $this->replaceholder($controller_stub));
        $this->line('Controller file created: <info>'.$controller_file.'</info>');
    }

    protected function justCopy($path)
    {
        $stub_path = $this->stub_path.'/'.$path;
        $brand_path = $this->brand_path.'/'.$path;
        if (!$this->files->exists($brand_path)) {
            $this->files->makeDirectory($brand_path, 0755, true);
        }
        foreach ($this->files->files($stub_path) as $file) {
            $file = $file->getBasename();
            $stub = $stub_path.'/'.$file;
            $file = $brand_path.'/'.str_replace('.stub', '', $file);
            $stub = $this->files->get($stub);
            $this->files->put($file, $this->replaceholder($stub));
            $this->line($path.' file created: <info>'.$file.'</info>');
        }
    }

    protected function package()
    {
        $file = $this->brand_path.'/package.json';
        $stub = $this->stub_path.'/package.json.stub';
        $stub = $this->files->get($stub);
        $this->files->put($file, $this->replaceholder($stub));
        $this->line('package.json file created: <info>'.$file.'</info>');
    }

    protected function webpack()
    {
        $file = $this->brand_path.'/webpack.mix.js';
        $stub = $this->stub_path.'/webpack.mix.js.stub';
        $stub = $this->files->get($stub);
        $this->files->put($file, $this->replaceholder($stub));
        $this->line('webpack.mix.js file created: <info>'.$file.'</info>');
    }

    protected function others()
    {
        $files = [
            '.gitattributes',
            '.gitignore',
        ];
        foreach ($files as $file) {
            $asset_stub = $this->stub_path.'/'.$file;
            $asset_dir = $this->brand_path.'/'.$file;
            $this->files->copy($asset_stub, $asset_dir);
            $this->line('.gitattributes copied: <info>'.$asset_dir.'</info>');
        }
    }

    protected function replaceholder($content)
    {
        foreach ($this->replaces as $search => $replace) {
            $content = str_replace($search, $replace, $content);
        }
        return $content;
    }
}
