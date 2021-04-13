<?php

namespace Wikichua\SAP;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class SAPServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app['router']->aliasMiddleware('intend_url', 'Wikichua\SAP\Middleware\IntendUrl');
        $this->app['router']->aliasMiddleware('auth', 'Wikichua\SAP\Middleware\Authenticate');
        $this->app['router']->aliasMiddleware('auth_admin', 'Wikichua\SAP\Middleware\AuthAdmin');
        $this->app['router']->aliasMiddleware('reauth_admin', 'Wikichua\SAP\Middleware\ReAuth');
        // $this->app['router']->aliasMiddleware('optimizeImages', 'Spatie\LaravelImageOptimizer\Middlewares\OptimizeImages');

        $this->app['router']->pushMiddlewareToGroup('web', \Wikichua\SAP\Middleware\PhpDebugBar::class);
        $this->app['router']->pushMiddlewareToGroup('web', \Wikichua\SAP\Middleware\HttpsProtocol::class);
        $this->app['router']->pushMiddlewareToGroup('web', \Spatie\Honeypot\ProtectAgainstSpam::class);
        $this->app['router']->pushMiddlewareToGroup('web', \Spatie\LaravelImageOptimizer\Middlewares\OptimizeImages::class);
        $this->app['router']->pushMiddlewareToGroup('api', \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class);

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
            $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        }

        $this->loadComponents();
        $this->gatePermissions();
        $this->validatorExtensions();
        $this->configSettings();
        $this->bladeDirectives();

        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'sap');
        $this->loadViewsFrom(__DIR__.'/../resources/views/sap', 'sap');

        if ((isset(parse_url(config('app.url'))['host']) && parse_url(config('app.url'))['host'] == request()->getHost())) {
            $this->mergeConfigFrom(__DIR__.'/../config/services.php', 'services');
            $this->loadRoutes();
            $this->loadRoutesFrom(__DIR__.'/pub.php');
            $this->loadRoutesFrom(__DIR__.'/web.php');
            $this->loadRoutesFrom(__DIR__.'/api.php');

            Paginator::useBootstrap();
        }
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
    }

    public function provides()
    {
        return ['sap'];
    }

    protected function bootForConsole()
    {
        // Registering package commands.
        $this->commands([
            Commands\SapConfig::class,
            Commands\SapMake::class,
            Commands\SapBrand::class,
            Commands\SapComponent::class,
            Commands\SapIndex::class,
            Commands\SapReport::class,
            Commands\SapService::class,
            Commands\SapExport::class,
            Commands\SapImport::class,
            Commands\SapMailer::class,
            Commands\SapPusher::class,
            Commands\SapVhost::class,
        ]);

        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/sap.php' => config_path('sap.php'),
        ], 'sap.export.config');

        // Publishing the views.
        $this->publishes([
            __DIR__.'/../resources/views/sap' => base_path('resources/views/vendor/sap'),
        ], 'sap.export.view');

        $this->publishes([
            __DIR__.'/../resources/views/sap/components' => base_path('resources/views/vendor/sap/components'),
        ], 'sap.export.component');

        $this->publishes([
            __DIR__.'/../resources/views/sap/components/menus' => base_path('resources/views/vendor/sap/components/menus'),
        ], 'sap.export.menus');

        $this->publishes([
            __DIR__.'/Models' => app_path('Models/Sap'),
            __DIR__.'/../config/sap.php' => config_path('sap.php'),
        ], 'sap.export.model');

        $this->publishes([
            // __DIR__.'/../.php_cs' => base_path('.php_cs'),
        ], 'sap.update');

        $this->publishes([
            __DIR__.'/../resources/views/sap/components/admin-menu.blade.php' => base_path('resources/views/vendor/sap/components/admin-menu.blade.php'),
            __DIR__.'/../resources/js' => base_path('resources/js'),
            __DIR__.'/../resources/sass' => base_path('resources/sass'),
            __DIR__.'/../package.json' => base_path('package.json'),
            __DIR__.'/../webpack.mix.js' => base_path('webpack.mix.js'),
            __DIR__.'/../.php_cs' => base_path('.php_cs'),
            // php debugbar
            base_path('vendor/barryvdh/laravel-debugbar/config/debugbar.php') => config_path('debugbar.php'),
            // sanctum
            base_path('vendor/laravel/sanctum/database/migrations') => database_path('migrations'),
            base_path('vendor/laravel/sanctum/config/sanctum.php') => config_path('sanctum.php'),
            // filemanager
            base_path('vendor/unisharp/laravel-filemanager/src/config/lfm.php') => config_path('lfm.php'),
            base_path('vendor/unisharp/laravel-filemanager/public') => public_path('vendor/laravel-filemanager'),
            // Lionix\SeoManager
            __DIR__.'/../config/seo-manager.php' => config_path('seo-manager.php'),
            base_path('vendor/lionix/seo-manager/src/assets') =>  public_path('vendor/lionix'),
            // realrashid/sweet-alert
            base_path('vendor/realrashid/sweet-alert/src/config/sweetalert.php') => config_path('sweetalert.php'),
            // spatie/laravel-honeypot but using modified honeypot config as don't return blankpage
            __DIR__.'/../config/honeypot.php' => config_path('honeypot.php'),
        ], 'sap.install');

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/sap'),
        ], 'sap.views');*/
    }

    protected function loadRoutes()
    {
        $files = File::files(__DIR__.'/routes/');
        foreach ($files as $file) {
            Route::middleware('web')
                ->group($file->getPathname());
        }
        $files = File::files(__DIR__.'/routes/api');
        foreach ($files as $file) {
            Route::middleware('api')
                ->group($file->getPathname());
        }
        if (File::exists(app_path('../routes/sap'))) {
            $files = File::files(app_path('../routes/sap/'));
            foreach ($files as $file) {
                Route::middleware('web')
                    ->group($file->getPathname());
            }
        }
        if (File::exists(app_path('../routes/sap/api'))) {
            $files = File::files(app_path('../routes/sap/api'));
            foreach ($files as $file) {
                Route::middleware('api')
                    ->group($file->getPathname());
            }
        }
    }

    protected function loadComponents()
    {
        \Blade::componentNamespace('Wikichua\\SAP\\View\\Components', 'sap');
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
            cache()->rememberForever('config-settings', function () {
                $settings = app(config('sap.models.setting'))->all();
                foreach ($settings as $setting) {
                    Config::set('settings.'.$setting->key, $setting->value);
                }
            });
        }
    }

    protected function bladeDirectives()
    {
        Blade::directive('impersonating', function ($guard = null) {
            return "<?php if (is_impersonating({$guard})) : ?>";
        });

        Blade::directive('endImpersonating', function () {
            return '<?php endif; ?>';
        });

        Blade::directive('canImpersonate', function ($guard = null) {
            return "<?php if (can_impersonate({$guard})) : ?>";
        });

        Blade::directive('endCanImpersonate', function () {
            return '<?php endif; ?>';
        });

        Blade::directive('canBeImpersonated', function ($expression) {
            $args = preg_split("/,(\s+)?/", $expression);
            $guard = $args[1] ?? null;

            return "<?php if (can_be_impersonated({$args[0]}, {$guard})) : ?>";
        });

        Blade::directive('endCanBeImpersonated', function () {
            return '<?php endif; ?>';
        });
    }
}
