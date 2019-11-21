<?php

namespace LaravelDaily\Invoices;

use Illuminate\Support\ServiceProvider;

/**
 * Class InvoiceServiceProvider
 * @package LaravelDaily\Invoices
 */
class InvoiceServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'invoices');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'invoices');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/invoices.php', 'invoices');

        // Register the service the package provides.
        $this->app->singleton('invoice', function ($app) {
            return new Invoice;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['invoice'];
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
            __DIR__ . '/../config/invoices.php' => config_path('invoices.php'),
        ], 'invoices.config');

        // Publishing the views.
        $this->publishes([
            __DIR__ . '/../resources/views' => base_path('resources/views/vendor/LaravelDaily'),
        ], 'invoices.views');

        // Publishing assets.
        $this->publishes([
            __DIR__ . '/../resources/assets' => public_path('vendor/LaravelDaily'),
        ], 'invoices.views');

        // Publishing the translation files.
        $this->publishes([
            __DIR__ . '/../resources/lang' => resource_path('lang/vendor/LaravelDaily'),
        ], 'invoices.views');

        // Registering package commands.
        // $this->commands([]);
    }
}
