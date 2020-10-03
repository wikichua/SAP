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
        if (Schema::hasTable('brands') && File::isDirectory(base_path('brand'))) {
            if (\Str::of(env('APP_URL'))->is('*'.request()->getHost())) { // load from admin route
                $brands = app(config('sap.models.brand'))->all();
                foreach ($brands as $brand) {
                    $brandName = strtolower($brand->name);
                    $dir = base_path('brand/'.$brandName);
                    if (File::exists($dir.'/web.php')) {
                        \Route::middleware('web')->group($dir.'/web.php');
                    }
                    // $this->registerBrandServiceProviders($dir);
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
        if (File::isDirectory($dir.'/providers')) {
            foreach (File::files($dir.'/providers') as $file) {
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
            $brandDomain = request()->getHost();
            $brand = \Cache::remember('brand-'.$brandDomain, (60*60*24), function () use ($brandDomain) {
                return app(config('sap.models.brand'))->query()->whereStatus('A')->whereDomain($brandDomain)->where('published_at', '<', date('Y-m-d 23:59:59'))->where('expired_at', '>', date('Y-m-d 23:59:59'))->first();
            });
            if ($brand) {
                $brandName = strtolower($brand->name);
                $dir = base_path('brand/'.$brandName);
                if (File::exists($dir.'/web.php')) {
                    $this->registerBrandServiceProviders($dir);
                    // $dotenv = \Dotenv\Dotenv::createImmutable($dir, '.env');
                    // $dotenv->load();
                    // $this->app->loadEnvironmentFrom($dir.'/.env');
                    \Route::middleware('web')->group($dir.'/web.php');
                    // $this->loadTranslationsFrom($dir.'/lang', $brandName);
                    // $this->loadViewsFrom($dir, $brandName);
                }
            }
        }
    }
}