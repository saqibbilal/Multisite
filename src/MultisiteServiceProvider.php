<?php

namespace Apsg\Multisite;

use Illuminate\Support\ServiceProvider;
use Illuminate\View\FileViewFinder;

class MultisiteServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }

        $this->registerHelpers();
    }

    public function registerHelpers()
    {
        require_once 'helpers.php';
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/multisite.php', 'multisite');

        // Register the service the package provides.
        $this->app->singleton('multisite', function ($app) {
            return new Multisite;
        });

        $this->app->bind('view.finder', function ($app) {
            $paths = $app['config']['view.paths'];

            $paths[] = resource_path('views/' . $app['config']['multisite.domain']);

            return new FileViewFinder($app['files'], $paths);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['multisite'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__ . '/../config/multisite.php' => config_path('multisite.php'),
        ], 'multisite.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/gacek'),
        ], 'multisite.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/gacek'),
        ], 'multisite.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/gacek'),
        ], 'multisite.views');*/

        // Registering package commands.
        // $this->commands([]);
    }
}
