<?php namespace jlourenco\base;

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
        $config = $this->app['config']->get('jlourenco.base');
        $users = array_get($config, 'models.User');

        \Sentinel::setModel($users);
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->prepareResources();
        $this->registerGroups();
        $this->registerUsers();
    }

    /**
     * Prepare the package resources.
     *
     * @return void
     */
    protected function prepareResources()
    {
        // Publish our views
        $this->loadViewsFrom(base_path("resources/views"), 'base');
        $this->publishes([
            __DIR__ .  '/views' => base_path("resources/views")
        ]);

        // Publish our lang
        $this->publishes([
            __DIR__ .  '/lang' => base_path("resources/lang")
        ], 'migrations');

        // Publish our migrations
        $this->publishes([
            __DIR__ .  '/migrations' => base_path("database/migrations")
        ], 'migrations');

        // Publish a config file
        $this->publishes([
            __DIR__ . '/config' => base_path('/config')
        ], 'config');

        // Publish our routes
        $this->publishes([
            __DIR__ .  '/routes.php' => base_path("app/Http/base_routes.php")
        ], 'routes');

        // Include the routes file
        if(file_exists(base_path("app/Http/base_routes.php")))
            include base_path("app/Http/base_routes.php");
    }

    /**
     * Registers the users.
     *
     * @return void
     */
    protected function registerUsers()
    {
        $this->app->singleton('jlourenco.users', function ($app) {
            $config = $app['config']->get('jlourenco.base');

            $users = array_get($config, 'models.User');
            $model = array_get($config, 'models.Group');

            if (class_exists($model) && method_exists($model, 'setUsersModel'))
                forward_static_call_array([$model, 'setUsersModel'], [$users]);

            return new IlluminateUserRepository($users);
        });
    }

    /**
     * Registers the groups.
     *
     * @return void
     */
    protected function registerGroups()
    {
        $this->app->singleton('jlourenco.groups', function ($app) {
            $config = $app['config']->get('jlourenco.base');

            $model = array_get($config, 'base.models.Group');

            return new IlluminateRoleRepository($model);
        });
    }

    /**
     * {@inheritDoc}
     */
    public function provides()
    {
        return [
            'jlourenco.users',
            'jlourenco.groups',
        ];
    }

}