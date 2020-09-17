<?php

namespace Wikichua\SAP\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SapService extends Command
{
    protected $signature = 'sap:service {name} {--force}';
    protected $description = 'Create Service Facade Class';
    protected $name;

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->name = \Str::studly($this->argument('name'));
        if (File::exists(app_path(config('sap.custom_service_dir'))) != true) {
            File::makeDirectory(app_path(config('sap.custom_service_dir')));
        }
        if (File::exists(app_path(config('sap.custom_facade_dir'))) != true) {
            File::makeDirectory(app_path(config('sap.custom_facade_dir')));
        }
        if ($this->option('force') == false) {
            if (File::exists(app_path(config('sap.custom_service_dir').'/'.$this->name.'.php')) || File::exists(app_path(config('sap.custom_facade_dir').'/'.$this->name.'.php'))) {
                $this->info('Service has already exists');
                return ;
            }
        }
        File::put(app_path(config('sap.custom_facade_dir').'/'.$this->name.'.php'), $this->facadeString());
        File::put(app_path(config('sap.custom_service_dir').'/'.$this->name.'.php'), $this->serviceString());
    }
    protected function facadeString()
    {
        $namespace = config('sap.custom_facade_namespace');
        $name = $this->name;
        return <<<EOT
<?php

namespace {$namespace};

use Illuminate\Support\Facades\Facade;

class {$name} extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Services\\{$name}::class;
    }
}
EOT;
    }
    protected function serviceString()
    {
        $namespace = config('sap.custom_service_namespace');
        $name = $this->name;
        return <<<EOT
<?php

namespace {$namespace};

use Illuminate\Support\Collection;

class {$name}
{
    public function __construct()
    {
    }
    public function inspire()
    {
        return Collection::make([
            'Act only according to that maxim whereby you can, at the same time, will that it should become a universal law. - Immanuel Kant',
            'An unexamined life is not worth living. - Socrates',
            'Be present above all else. - Naval Ravikant',
            'Happiness is not something readymade. It comes from your own actions. - Dalai Lama',
            'He who is contented is rich. - Laozi',
            'I begin to speak only when I am certain what I will say is not better left unsaid - Cato the Younger',
            'If you do not have a consistent goal in life, you can not live it in a consistent way. - Marcus Aurelius',
            'It is not the man who has too little, but the man who craves more, that is poor. - Seneca',
            'It is quality rather than quantity that matters. - Lucius Annaeus Seneca',
            'Knowing is not enough; we must apply. Being willing is not enough; we must do. - Leonardo da Vinci',
            'Let all your things have their places; let each part of your business have its time. - Benjamin Franklin',
            'No surplus words or unnecessary actions. - Marcus Aurelius',
            'Order your soul. Reduce your wants. - Augustine',
            'People find pleasure in different ways. I find it in keeping my mind clear. - Marcus Aurelius',
            'Simplicity is an acquired taste. - Katharine Gerould',
            'Simplicity is the consequence of refined emotions. - Jean D\'Alembert',
            'Simplicity is the essence of happiness. - Cedric Bledsoe',
            'Simplicity is the ultimate sophistication. - Leonardo da Vinci',
            'Smile, breathe, and go slowly. - Thich Nhat Hanh',
            'The only way to do great work is to love what you do. - Steve Jobs',
            'The whole future lies in uncertainty: live immediately. - Seneca',
            'Very little is needed to make a happy life. - Marcus Antoninus',
            'Waste no more time arguing what a good man should be, be one. - Marcus Aurelius',
            'Well begun is half done. - Aristotle',
            'When there is no desire, all things are at peace. - Laozi',
        ])->random();
    }
}

EOT;
    }
}
