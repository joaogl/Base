<?php namespace jlourenco\base;

use Illuminate\Support\ServiceProvider;
use jlourenco\base\Repositories\SettingsRepository;
use jlourenco\base\Repositories\UserRepository;

class baseServiceProvider extends ServiceProvider
{

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {

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
        $this->registerSettings();
        $this->registerBase();
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
        $this->app->singleton('jlourenco.user', function ($app) {
            $config = $app['config']->get('jlourenco.base');

            $users = array_get($config, 'models.User');
            $model = array_get($config, 'models.Group');

            if (class_exists($model) && method_exists($model, 'setUsersModel'))
                forward_static_call_array([$model, 'setUsersModel'], [$users]);

            return new UserRepository($users);
        });
    }

    /**
     * Registers the groups.
     *
     * @return void
     */
    protected function registerGroups()
    {
        $this->app->singleton('jlourenco.group', function ($app) {
            $config = $app['config']->get('jlourenco.base');

            $model = array_get($config, 'base.models.Group');

            return new IlluminateRoleRepository($model);
        });
    }

    /**
     * Registers the settings.
     *
     * @return void
     */
    protected function registerSettings()
    {
        $this->app->singleton('jlourenco.settings', function ($app) {
            $config = $app['config']->get('jlourenco.base');

            $model = array_get($config, 'models.Settings');

            return new SettingsRepository($model);
        });
    }

    /**
     * Registers base.
     *
     * @return void
     */
    protected function registerBase()
    {
        $this->app->singleton('base', function ($app) {
            $base = new Base($app['jlourenco.settings'], $app['jlourenco.user']);

            return $base;
        });

        $this->app->alias('base', 'jlourenco\base\Base');
    }

    /**
     * {@inheritDoc}
     */
    public function provides()
    {
        return [
            'jlourenco.user',
            'jlourenco.group',
            'jlourenco.settings',
            'base'
        ];
    }

}