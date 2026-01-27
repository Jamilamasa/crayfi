<?php

namespace Cray\Laravel;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Client\Factory;
use Cray\Laravel\Http\CrayClient;
use Cray\Laravel\Support\ResponseNormalizer;

class CrayServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/cray.php', 'cray');

        $this->app->singleton(ResponseNormalizer::class, function () {
            return new ResponseNormalizer();
        });

        $this->app->singleton(CrayClient::class, function ($app) {
            return new CrayClient(
                $app->make(Factory::class),
                $app->make(ResponseNormalizer::class),
                $app['config']['cray'] ?? []
            );
        });

        $this->app->singleton('cray', function ($app) {
            return new Cray($app->make(CrayClient::class));
        });
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/cray.php' => config_path('cray.php'),
            ], 'cray-config');
        }
    }
}
