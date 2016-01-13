<?php

namespace jlourenco\base;

use Illuminate\Support\ServiceProvider;

class baseServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish our routes
        // if (!$this->app->routesAreCached())
		require __DIR__ . '/routes.php';

        // Publish our views
        $this->loadViewsFrom(base_path("resources/views"), 'base');
        $this->publishes([
            __DIR__ .  '/views' => base_path("resources/views")
        ]);

        // Publish our migrations
        $this->publishes([
            __DIR__ .  '/migrations' => base_path("database/migrations")
        ], 'migrations');

        // Publish a config file
        $this->publishes([
            __DIR__ . '/config' => base_path('/config')
        ], 'config');
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        // Bind the
        $this->app->bind('base', function(){
            return new base;
        });
    }
}