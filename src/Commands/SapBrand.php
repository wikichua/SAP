<?php

namespace Wikichua\SAP\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;

class SapBrand extends Command
{
    protected $signature = 'sap:brand {brand} {--domain=} {--force}';
    protected $description = 'Make Up The BRAND';

    public function __construct()
    {
        parent::__construct();
        $this->files = new Filesystem;
        $this->stub_path = config('sap.stub_path').'/brand';
    }

    public function handle()
    {
        $this->brand = \Str::studly($this->argument('brand'));
        $this->domain = !empty($this->option('domain'))? $this->option('domain'):(strtolower($this->brand).'.test');
        $this->replaces['{%domain%}'] = $domain = $this->domain;
        $this->replaces['{%brand_name%}'] = $brand_name = $this->brand;
        $this->replaces['{%brand_capital%}'] = $brand_capital = strtoupper($this->brand);
        $this->replaces['{%brand_string%}'] = strtolower($this->brand);
        $this->brand_path = $brand_path = base_path('brand/'.$this->brand);
        if (!$this->files->exists($brand_path)) {
            $this->files->makeDirectory($brand_path, 0755, true);
        } else {
            $this->info('Brand <info>'.$this->brand.'</info> has already existed!');
            if (false == $this->option('force')) {
                return;
            }
            $this->info('So you\'ve decided to overwrite it!');
        }
        $this->autoload();
        $this->route();
        $this->model();
        $this->controller();
        $this->serviceprovider();
        $this->middleware();
        $this->resources();
        $this->package();
        $this->webpack();
        $this->others();
        $this->component();
        $this->seed();
        \Cache::forget('brand-configs');
        \Cache::forget('brand-'.$brand_name);
        if ($this->confirm('Do you wish to run composer dumpautoload for '.$this->brand_path.'?')) {
            shell_exec('composer dumpautoload; cd '.$this->brand_path. '; npm run dev');
        }
    }

    protected function autoload()
    {
        $composerjson = base_path('composer.json');
        if (File::exists($composerjson) == false || File::isWritable($composerjson) == false) {
            $this->error('composer.json undetected or is not writable');
            return;
        }
        $str[] = '"psr-4": {';
        $str[] = "\t\t\t".'"Brand\\\": "brand/",';
        if (strpos(File::get($composerjson), '"Brand\\\": "brand/",') == false) {
            $content = \Str::replaceFirst($str[0], implode(PHP_EOL, $str), File::get($composerjson));
            File::replace($composerjson, $content);
        }
    }

    protected function resources()
    {
        $this->assets();
        $this->justCopy('resources/views/layouts');
        $this->justCopy('resources/views/pages');
        $this->justCopy('resources/views/components');
        $this->justCopy('resources/views/admin');
        $this->justCopy('resources/lang');
        $this->justCopy('config');
    }

    protected function assets()
    {
        $asset_stub = $this->stub_path.'/resources';
        $asset_dir = $this->brand_path.'/resources';
        $this->files->copyDirectory($asset_stub, $asset_dir);
        if (!$this->files->exists($this->brand_path.'/public')) {
            $this->files->makeDirectory($this->brand_path.'/public');
        }
        if (!$this->files->exists(public_path($this->replaces['{%brand_name%}']))) {
            if ($this->files->exists($this->brand_path.'/public')) {
                shell_exec('ln -s '.$this->brand_path.'/public' .' '.public_path($this->replaces['{%brand_name%}']));
                $this->line('symlink created: <info>'.public_path($this->replaces['{%brand_name%}']).'</info>');
            }
        }
        $this->line('Assets copied: <info>'.$asset_dir.'</info>');
    }

    protected function component()
    {
        // if (!$this->files->exists($this->brand_path.'/components')) {
        //     $this->files->makeDirectory($this->brand_path.'/components');
        //     $this->line('Component created: <info>'.$this->brand_path.'/components'.'</info>');
        // }
        $this->justCopy('Components');
    }

    protected function route()
    {
        $this->files->ensureDirectoryExists($this->brand_path.'/routes');
        $route_file = $this->brand_path.'/routes/web.php';
        $route_stub = $this->stub_path.'/web.php.stub';
        if (!$this->files->exists($route_stub)) {
            $this->error('Web stub file not found: <info>'.$route_stub.'</info>');
            return;
        }
        $route_stub = $this->files->get($route_stub);
        $this->files->put($route_file, $this->replaceholder($route_stub));
        $this->line('Web file created: <info>'.$route_file.'</info>');
    }

    protected function model()
    {
        $dir = 'brand/'.$this->brand.'/Models';
        $this->files->ensureDirectoryExists(base_path($dir), 0755, true);
        $this->justCopy('Models');
    }

    protected function controller()
    {
        $controller_stub = $this->stub_path.'/controller.stub';
        if (!$this->files->exists($controller_stub)) {
            $this->error('Controller stub file not found: <info>'.$controller_stub.'</info>');
            return;
        }
        $controller_dir = 'brand/'.$this->brand.'/Controllers';

        $this->files->ensureDirectoryExists(base_path($controller_dir), 0755, true);

        $controller_file = base_path($controller_dir.'/'.$this->brand.'Controller.php');
        $controller_stub = $this->files->get($controller_stub);
        $this->files->put($controller_file, $this->replaceholder($controller_stub));
        $this->line('Controller file created: <info>'.$controller_file.'</info>');

        $this->justCopy('Controllers');
    }

    protected function serviceprovider()
    {
        $stub = $this->stub_path.'/serviceprovider.stub';
        if (!$this->files->exists($stub)) {
            $this->error('Service Provider stub file not found: <info>'.$stub.'</info>');
            return;
        }
        $dir = 'brand/'.$this->brand.'/Providers';
        if (!$this->files->exists(base_path($dir))) {
            $this->files->makeDirectory(base_path($dir), 0755, true);
        }
        $file = base_path($dir.'/'.$this->brand.'ServiceProvider.php');
        $stub = $this->files->get($stub);
        $this->files->put($file, $this->replaceholder($stub));
        $this->line('Service Provider file created: <info>'.$file.'</info>');
    }

    protected function middleware()
    {
        $stub = $this->stub_path.'/middleware.stub';
        if (!$this->files->exists($stub)) {
            $this->error('Middleware stub file not found: <info>'.$stub.'</info>');
            return;
        }
        $dir = 'brand/'.$this->brand.'/Middlewares';
        if (!$this->files->exists(base_path($dir))) {
            $this->files->makeDirectory(base_path($dir), 0755, true);
        }
        $file = base_path($dir.'/'.$this->brand.'Middleware.php');
        $stub = $this->files->get($stub);
        $this->files->put($file, $this->replaceholder($stub));
        $this->line('Middleware file created: <info>'.$file.'</info>');

        $this->justCopy('Middlewares');
    }

    protected function justCopy($path)
    {
        $stub_path = $this->stub_path.'/'.$path;
        $brand_path = $this->brand_path.'/'.$path;
        $this->files->ensureDirectoryExists($brand_path, 0755, true);
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

    protected function seed()
    {
        $msg = 'Migration file created';
        $migration_stub = $this->stub_path.'/brand_seed.stub';
        if (!$this->files->exists($migration_stub)) {
            $this->error('Migration stub file not found: <info>'.$migration_stub.'</info>');
            return;
        }
        $filename = "sap{$this->brand}BrandSeed.php";
        $this->files->ensureDirectoryExists(base_path('brand/'.$this->brand.'/database/migrations/'), 0755, true);
        $migration_file = base_path('brand/'.$this->brand.'/database/migrations/'.date('Y_m_d_000000_').$filename);
        foreach ($this->files->files(base_path('brand/'.$this->brand.'/database/migrations/')) as $file) {
            if (str_contains($file->getPathname(), $filename)) {
                $migration_file = $file->getPathname();
                $msg = 'Migration file overwritten';
            }
        }

        $migrations_stub = $this->files->get($migration_stub);
        $this->files->put($migration_file, $this->replaceholder($migrations_stub));
        $this->line($msg.': <info>'.$migration_file.'</info>');
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
            $this->line($file.' copied: <info>'.$asset_dir.'</info>');
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
