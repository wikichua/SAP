<?php

namespace Wikichua\SAP;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class SAPServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app['router']->aliasMiddleware('intend_url', 'Wikichua\SAP\Middleware\IntendUrl');
        $this->app['router']->aliasMiddleware('auth', 'Wikichua\SAP\Middleware\Authenticate');
        $this->app['router']->aliasMiddleware('auth_admin', 'Wikichua\SAP\Middleware\AuthAdmin');

        $this->app['router']->pushMiddlewareToGroup('web', \Wikichua\SAP\Middleware\PhpDebugBar::class);
        $this->app['router']->pushMiddlewareToGroup('web', \Wikichua\SAP\Middleware\HttpsProtocol::class);
        $this->app['router']->pushMiddlewareToGroup('api', \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class);

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }

        $this->loadComponents();
        $this->gatePermissions();
        $this->validatorExtensions();
        $this->configSettings();
        $this->bladeDirectives();

        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'sap');
        $this->loadViewsFrom(__DIR__.'/../resources/views/sap', 'sap');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        if (\Str::of(env('APP_URL'))->is('*'.request()->getHost())) {
            $this->mergeConfigFrom(__DIR__.'/../config/services.php', 'services');
            $this->loadRoutes();
            $this->loadRoutesFrom(__DIR__.'/pub.php');
            $this->loadRoutesFrom(__DIR__.'/web.php');
            $this->loadRoutesFrom(__DIR__.'/api.php');

            Paginator::useBootstrap();
        }

        // Registering package commands.
        $this->commands([
            Commands\SapConfig::class,
            Commands\SapMake::class,
            Commands\SapBrand::class,
            Commands\SapComponent::class,
            Commands\SapES::class,
            Commands\SapReport::class,
            Commands\SapService::class,
        ]);
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/sap.php', 'sap');
        $this->mergeConfigFrom(__DIR__.'/../config/laravel-impersonate.php', 'impersonate');

        $this->app->singleton('sap', function ($app) {
            return new SAP();
        });
        $this->app->register(\Wikichua\SAP\Providers\HelpServiceProvider::class);
        $this->app->register(\Wikichua\SAP\Providers\BrandServiceProvider::class);
        $this->app->register(\Matchish\ScoutElasticSearch\ElasticSearchServiceProvider::class);
    }

    public function provides()
    {
        return ['sap'];
    }

    protected function bootForConsole()
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/sap.php' => config_path('sap.php'),
        ], 'sap.config');

        // Publishing the views.
        $this->publishes([
            __DIR__.'/../resources/views/sap' => base_path('resources/views/vendor/sap'),
        ], 'sap.view');

        $this->publishes([
            __DIR__.'/../resources/views/sap/components' => base_path('resources/views/vendor/sap/components'),
        ], 'sap.component');

        $this->publishes([
            __DIR__.'/../resources/views/sap/components/menus' => base_path('resources/views/vendor/sap/components/menus'),
        ], 'sap.menus');

        $this->publishes([
            __DIR__.'/../resources/views/sap/components/admin-menu.blade.php' => base_path('resources/views/vendor/sap/components/admin-menu.blade.php'),
            __DIR__.'/../resources/js' => base_path('resources/js'),
            __DIR__.'/../resources/sass' => base_path('resources/sass'),
            __DIR__.'/../package.json' => base_path('package.json'),
            __DIR__.'/../webpack.mix.js' => base_path('webpack.mix.js'),
            // php debugbar
            base_path('vendor/barryvdh/laravel-debugbar/config/debugbar.php') => config_path('debugbar.php'),
            // sanctum
            base_path('vendor/laravel/sanctum/database/migrations') => database_path('migrations'),
            base_path('vendor/laravel/sanctum/config/sanctum.php') => config_path('sanctum.php'),
            // filemanager
            base_path('vendor/unisharp/laravel-filemanager/src/config/lfm.php') => config_path('lfm.php'),
            base_path('vendor/unisharp/laravel-filemanager/public') => public_path('vendor/laravel-filemanager'),
            // scout
            base_path('vendor/laravel/scout/config/scout.php') => config_path('scout.php'),
            // Matchish\ScoutElasticSearch elasticsearch
            base_path('vendor/matchish/laravel-scout-elasticsearch/config/elasticsearch.php') => config_path('elasticsearch.php'),
            // Lionix\SeoManager
            __DIR__.'/../config/seo-manager.php' => config_path('seo-manager.php'),
            base_path('vendor/lionix/seo-manager/src/assets') =>  public_path('vendor/lionix'),
            // realrashid/sweet-alert
            base_path('vendor/realrashid/sweet-alert/src/config/sweetalert.php') => config_path('sweetalert.php'),
        ], 'sap.install');

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/sap'),
        ], 'sap.views');*/
    }

    protected function loadRoutes()
    {
        $files = File::files(__DIR__.'/routers/');
        foreach ($files as $file) {
            Route::middleware('web')
                ->group($file->getPathname());
        }
        $files = File::files(__DIR__.'/routers/api');
        foreach ($files as $file) {
            Route::middleware('api')
                ->group($file->getPathname());
        }
        if (File::exists(app_path('../routes/routers'))) {
            $files = File::files(app_path('../routes/routers/'));
            foreach ($files as $file) {
                Route::middleware('web')
                    ->group($file->getPathname());
            }
        }
        if (File::exists(app_path('../routes/routers/api'))) {
            $files = File::files(app_path('../routes/routers/api'));
            foreach ($files as $file) {
                Route::middleware('api')
                    ->group($file->getPathname());
            }
        }
    }

    protected function loadComponents()
    {
        \Blade::componentNamespace('Wikichua\\SAP\\View\\Components', 'sap');
        // $array = [];
        // foreach (File::files(__DIR__.'/View/Components/') as $file) {
        //     $basename = str_replace('.'.$file->getExtension(), '', $file->getBasename());
        //     $class = config('sap.component_namespace').'\\'.$basename;
        //     Blade::component('sap-'.\Str::snake($basename, '-'), get_class(new $class()));
        //     // $array[\Str::snake($basename, '-')] = $class;
        // }
        // $this->loadViewComponentsAs('sap', $array); // not using this as in double looping the components
    }

    protected function gatePermissions()
    {
        Gate::before(function ($user, $permission) {
            if ($user->hasPermission($permission)) {
                return true;
            }
        });
    }

    protected function validatorExtensions()
    {
        Validator::extend('current_password', function ($attribute, $value, $parameters, $validator) {
            return Hash::check($value, auth()->user()->password);
        }, 'The current password is invalid.');
    }

    protected function configSettings()
    {
        if (Schema::hasTable('settings')) {
            foreach (app(config('sap.models.setting'))->all() as $setting) {
                cache()->rememberForever('setting-'.$setting->key, function () use ($setting) {
                    return Config::set('settings.'.$setting->key, $setting->value);
                });
            }
        }
    }

    protected function bladeDirectives()
    {
    }
}
