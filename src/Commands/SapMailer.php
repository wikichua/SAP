<?php

namespace Wikichua\SAP\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SapMailer extends Command
{
    protected $signature = 'sap:mailer {name} {--brand=} {--force}';
    protected $description = 'Create Mail Class';
    protected $name;

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->brand = $this->option('brand')? $this->option('brand'):null;

        $this->name = \Str::studly($this->argument('name'));

        if ($this->brand) {
            $this->brandName = \Str::studly($this->option('brand'));
            $this->name .= $this->brandName;
            $this->brand($this->brandName);
        } else {
            $this->app();
        }
    }
    protected function app()
    {
        if (File::exists(app_path(config('sap.custom_mail_dir'))) != true) {
            File::makeDirectory(app_path(config('sap.custom_mail_dir')));
        }
        $mail_file = app_path(config('sap.custom_mail_dir').'/'.$this->name.'.php');
        if ($this->option('force') == false) {
            if (File::exists($mail_file)) {
                $this->info('Mail has already exists');
                return ;
            }
        }
        File::put($mail_file, $this->mailString());
        $this->info('Mail File added to '.$mail_file);

        $msg = 'Migration file created';
        $filename = "sap{$this->name}MailSeed.php";
        $database_dir = database_path('migrations');
        $migration_file = database_path('migrations/'.date('Y_m_d_000000_').$filename);
        foreach (File::files($database_dir) as $file) {
            if (str_contains($file->getPathname(), $filename)) {
                $migration_file = $file->getPathname();
                $msg = 'Migration file overwritten';
            }
        }
        File::put($migration_file, $this->seedString());
        $this->line($msg.': <info>'.$migration_file.'</info>');
    }
    protected function brand($brand_string)
    {
        $brand_service_path = base_path('brand/'.$brand_string.'/Mail');
        if (File::exists($brand_service_path) != true) {
            File::makeDirectory($brand_service_path);
        }
        $service_file = $brand_service_path.'/'.$this->name.'.php';
        if ($this->option('force') == false) {
            if (File::exists($service_file)) {
                $this->info('Mail has already exists');
                return ;
            }
        }
        File::put($service_file, $this->mailString(1));
        $this->info('Mail File added to '.$service_file);

        $msg = 'Migration file created';
        $filename = "sap{$this->name}MailSeed.php";
        $database_dir = base_path('brand/'.$this->brand.'/database/migrations');
        $migration_file = base_path('brand/'.$this->brand.'/database/migrations/'.date('Y_m_d_000000_').$filename);
        foreach (File::files($database_dir) as $file) {
            if (str_contains($file->getPathname(), $filename)) {
                $migration_file = $file->getPathname();
                $msg = 'Migration file overwritten';
            }
        }
        File::put($migration_file, $this->seedString(1));
        $this->line($msg.': <info>'.$migration_file.'</info>');
    }
    protected function mailString($isBrand = 0)
    {
        $name = $this->name;
        $namespace = config('sap.custom_mail_namespace');
        if ($isBrand) {
            $namespace = "Brand\\$this->brandName\Mail";
        }

        return <<<EOT
<?php

namespace {$namespace};

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\MailTemplates\TemplateMailable;

class {$name} extends TemplateMailable
{
    use Queueable, SerializesModels;

    public \$name;

    public function __construct()
    {
        \$user = app(config('sap.models.user'))->first();
        \$this->name = \$user->name;
    }

    public function getHtmlLayout(): string
    {
        // Blade view: `return view('mailLayouts.main', \$data)->render();`
        return '{{{ body }}}';
    }
}

EOT;
    }
    protected function seedString($isBrand = 0)
    {
        $name = $this->name;
        $namespace = config('sap.custom_mail_namespace');
        if ($isBrand) {
            $namespace = "Brand\\$this->brandName\Mail";
        }

        return <<<EOT
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class sap{$name}MailSeed extends Migration
{
    public function up()
    {
        app(config('sap.models.mailer'))->create([
            'mailable' => $namespace\\$name::class,
            'subject' => 'Welcome, {{ name }}',
            'html_template' => '<h1>Hello, {{ name }}!</h1>',
            'text_template' => 'Hello, {{ name }}!',
        ]);
    }
    public function down()
    {
        app(config('sap.models.mailer'))->where('mailable', \App\Mail\GreetingMail::class)->forceDelete();
    }
}


EOT;
    }
}
