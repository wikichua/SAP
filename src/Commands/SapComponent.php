<?php

namespace Wikichua\SAP\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class SapComponent extends Command
{
    protected $signature = 'sap:comp {name} {--brand=} {--inline} {--force}';
    protected $description = 'Make Up The COMPONENT';

    public function __construct()
    {
        parent::__construct();
        $this->files = new Filesystem;
        $this->stub_path = config('sap.stub_path').'/brand';
    }

    public function handle()
    {
        $this->brand = $this->option('brand')? $this->option('brand'):null;
        $this->replaces['{%brand_id%}'] = 0;
        if ($this->brand) {
            $brand = app(config('sap.models.brand'))->query()->where('name', strtolower($this->brand))->first();
            if (!$brand) {
                $this->error('Brand not found: <info>'.$this->brand.'</info>');
                return '';
            }
            $this->replaces['{%brand_id%}'] = $brand->id;
        }
        $this->comp_name = Str::studly($this->argument('name'));
        $this->replaces['{%comp_name%}'] = $comp_name = $this->comp_name;
        \Artisan::call('make:component', [
            'name' => $comp_name,
            '--inline' => $this->option('inline', false),
            '--force' => $this->option('force', false),
        ]);

        if ($this->brand) {
            $brand =  Str::studly($this->brand);
            $namespaceStr = "namespace Brand\\$brand\\Components;";
            $renderStr = "view('".strtolower($this->brand)."::components.".strtolower($comp_name)."')";

            $component_class_path = app_path('View/Components');
            $component_resource_path = resource_path('views/components');
            $brand_component_class_path = base_path('brand/'.$this->brand.'/Components');
            $brand_component_view_path = base_path('brand/'.$this->brand.'/resources/views/components');
            $this->files->ensureDirectoryExists($brand_component_class_path, 0755, true);
            $componentClass = $brand_component_class_path."/{$comp_name}.php";
            $componentView = $brand_component_view_path."/".(strtolower(Str::slug(Str::snake($comp_name)))).".blade.php";
            $this->files->move($component_class_path."/{$comp_name}.php", $componentClass);
            $this->files->move($component_resource_path."/".(strtolower(Str::slug(Str::snake($comp_name)))).".blade.php", $componentView);

            $content = $this->files->get($componentClass);
            $content = preg_replace('/^namespace\s.+;$/m', $namespaceStr, $content);
            $content = str_replace('view(\'components.'.strtolower($comp_name).'\')', $renderStr, $content);
            $this->files->put($componentClass, $content);
        }

        $this->output->write(\Artisan::output());
        $this->seed();
    }

    protected function seed()
    {
        $msg = 'Migration file created';
        $migration_stub = $this->stub_path.'/component_seed.stub';
        if (!$this->files->exists($migration_stub)) {
            $this->error('Migration stub file not found: <info>'.$migration_stub.'</info>');
            return;
        }
        $filename = "sap{$this->comp_name}ComponentSeed.php";

        $database_dir = database_path('migrations');
        $migration_file = database_path('migrations/'.date('Y_m_d_000000_').$filename);

        if ($this->brand) {
            $database_dir = base_path('brand/'.$this->brand.'/database');
            $migration_file = base_path('brand/'.$this->brand.'/database/'.date('Y_m_d_000000_').$filename);
        }
        foreach ($this->files->files($database_dir) as $file) {
            if (str_contains($file->getPathname(), $filename)) {
                $migration_file = $file->getPathname();
                $msg = 'Migration file overwritten';
            }
        }

        $migrations_stub = $this->files->get($migration_stub);
        $this->files->put($migration_file, $this->replaceholder($migrations_stub));
        $this->line($msg.': <info>'.$migration_file.'</info>');
    }

    protected function replaceholder($content)
    {
        foreach ($this->replaces as $search => $replace) {
            $content = str_replace($search, $replace, $content);
        }
        return $content;
    }
}
