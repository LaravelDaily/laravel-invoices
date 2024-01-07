<?php

namespace LaravelDaily\Invoices;

use Illuminate\Support\ServiceProvider;

/**
 * Class InvoiceServiceProvider
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
        $this->registerResources();
        $this->defineAssetPublishing();
    }

    /**
     * Register the Invoices routes.
     *
     * @return void
     */
    protected function registerResources()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'invoices');
        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'invoices');
    }

    /**
     * Define the asset publishing configuration.
     *
     * @return void
     */
    protected function defineAssetPublishing()
    {
        $this->publishes([
            INVOICES_PATH . '/public' => public_path('vendor/invoices'),
        ], 'invoices.assets');
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        if (! defined('INVOICES_PATH')) {
            define('INVOICES_PATH', realpath(__DIR__ . '/../'));
        }

        $this->configure();
        $this->offerPublishing();
        $this->registerServices();
        $this->registerCommands();
    }

    /**
     * Setup the configuration for Invoices.
     *
     * @return void
     */
    protected function configure()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/invoices.php', 'invoices');
    }

    /**
     * Setup the resource publishing groups for Invoices.
     *
     * @return void
     */
    protected function offerPublishing()
    {
        if ($this->app->runningInConsole()) {
            // Publishing the configuration file.
            $this->publishes([
                __DIR__ . '/../config/invoices.php' => config_path('invoices.php'),
            ], 'invoices.config');

            // Publishing the views.
            $this->publishes([
                __DIR__ . '/../resources/views' => base_path('resources/views/vendor/invoices'),
            ], 'invoices.views');

            // Publishing the translation files.
            $this->publishes([
                __DIR__ . '/../lang' => lang_path('vendor/invoices'),
            ], 'invoices.translations');
        }
    }

    /**
     * Register Invoices' services in the container.
     *
     * @return void
     */
    protected function registerServices()
    {
        $this->app->singleton('invoice', function ($app) {
            return new Invoice();
        });
    }

    /**
     * Register the Invoices Artisan commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\InstallCommand::class,
                Console\UpdateCommand::class,
            ]);
        }
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
}
