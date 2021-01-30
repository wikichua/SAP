<?php

namespace Wikichua\SAP\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class BrandServiceProvider extends ServiceProvider
{
    public function register()
    {
    }
    public function boot()
    {
        $brand_path = base_path('brand');
        if (Schema::hasTable('brands') && File::isDirectory($brand_path)) {
            if (app()->runningInConsole() || \Str::of(config('app.url'))->is('*'.request()->getHost())) { // load from admin route
                $dirs = File::directories($brand_path);
                foreach ($dirs as $dir) {
                    if (File::exists($dir.'/routes/web.php')) {
                        $this->loadRoutesFrom($dir.'/routes/web.php');
                    }
                    if (File::isDirectory($dir.'/database/migrations')) {
                        $this->loadMigrationsFrom($dir.'/database/migrations');
                    }
                    // load admin routes in brand
                    if (File::exists($dir.'/routes/sap')) {
                        $files = File::files($dir.'/routes/sap/');
                        foreach ($files as $file) {
                            $this->loadRoutesFrom($file);
                        }
                        if (File::exists($dir.'/routes/sap/api')) {
                            $files = File::files($dir.'/routes/sap/api/');
                            foreach ($files as $file) {
                                $this->loadRoutesFrom($file);
                            }
                        }
                    }
                }
                app(config('sap.models.brand'))->query()->whereStatus('A')->where('expired_at', '<', date('Y-m-d 23:59:59'))->update(['status' => 'E']);
            } else {
                $this->loadBrandStuffs();
            }
        }
    }
    protected function registerBrandServiceProviders($dir)
    {
        $brandName = basename($dir);
        if (File::isDirectory($dir.'/Providers')) {
            $files = File::files($dir.'/Providers');
            foreach ($files as $file) {
                if (str_contains($file->getFilename(), 'ServiceProvider.php')) {
                    list($namespace, $class) = array_values(preg_grep('/class|namespace/', explode(PHP_EOL, File::get($file->getPathname()))));
                    $class = explode(' ', $class)[1];
                    $namespace = '\\'.str_replace(['namespace ',';'], '', $namespace).'\\'.$class;
                    $this->app->register($namespace);
                }
            }
        }
        $this->loadMigrationsFrom($dir.'/database');
    }

    protected function loadBrandStuffs()
    {
        if (File::exists(base_path('brand'))) {
            $brandName = \Help::getBrandNameByHost(request()->getHost());
            $brand = \Help::brand($brandName);
            if ($brand) {
                $dir = base_path('brand/'.$brand->name);
                if (File::exists($dir.'/routes/web.php')) {
                    $this->registerBrandServiceProviders($dir);
                    // $dotenv = \Dotenv\Dotenv::createImmutable($dir, '.env');
                    // $dotenv->load();
                    // $this->app->loadEnvironmentFrom($dir.'/.env');
                    \Route::middleware('web')->group($dir.'/routes/web.php');
                    // $this->loadTranslationsFrom($dir.'/lang', $brandName);
                    // $this->loadViewsFrom($dir, $brandName);
                }
            }
        }
    }
}
