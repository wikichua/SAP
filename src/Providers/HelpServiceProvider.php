<?php

namespace Wikichua\SAP\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class HelpServiceProvider extends ServiceProvider
{
    public function boot()
    {
    }

    public function register()
    {
        $this->app->singleton('help', function ($app) {
            return new \Wikichua\SAP\Help();
        });
        // $this->app->booting(function () {
        $loader = AliasLoader::getInstance();
        $loader->alias('Help', \Wikichua\SAP\Facades\Help::class);
        // });
    }

    public function provides()
    {
        return ['help'];
    }
}
