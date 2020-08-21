<?php

namespace Wikichua\SAP\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class SapComponent extends Command
{
    protected $signature = 'sap:comp {name} {--inline} {--force}';
    protected $description = 'Make Up The COMPONENT';

    public function __construct()
    {
        parent::__construct();
        $this->files = new Filesystem;
        $this->stub_path = config('sap.stub_path').'/brand';
    }

    public function handle()
    {
        $this->comp_name = \Str::studly($this->argument('name'));
        $this->replaces['{%comp_name%}'] = $comp_name = $this->comp_name;
        \Artisan::call('make:component', [
            'name' => $comp_name,
            '--inline' => $this->option('inline', false),
            '--force' => $this->option('force', false),
        ]);
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
        $migration_file = database_path('migrations/'.date('Y_m_d_000000_').$filename);
        foreach ($this->files->files(database_path('migrations/')) as $file) {
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
