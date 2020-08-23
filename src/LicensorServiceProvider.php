<?php

namespace Sribna\Licensor;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Sribna\Licensor\Http\Middleware\CheckKeyFeature;

/**
 * Class LicensorServiceProvider
 * @package Sribna\Licensor
 */
class LicensorServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadRoutesFrom(__DIR__ . '/routes.php');

        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/licensor.php', 'licensor');

        /** @var Router $router */
        $router = $this->app['router'];
        $router->aliasMiddleware('licensor.key.feature', CheckKeyFeature::class);
    }

    /**
     * Get the services provided by the provider.
     * @return array
     */
    public function provides()
    {
        return ['licensor'];
    }

    /**
     * Console-specific booting.
     */
    protected function bootForConsole()
    {
        $this->publishes([
            __DIR__ . '/../config/licensor.php' => config_path('licensor.php'),
        ], 'licensor.config');
    }
}
