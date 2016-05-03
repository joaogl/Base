<?php namespace jlourenco\base;

use Illuminate\Support\ServiceProvider;
use jlourenco\base\Repositories\SettingsRepository;
use jlourenco\base\Repositories\UserRepository;
use jlourenco\base\Repositories\LogRepository;
use jlourenco\base\Repositories\VisitsRepository;
use jlourenco\base\Repositories\JobsRepository;
use Illuminate\Console\Scheduling\Schedule;

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
        $this->registerLog();
        $this->registerVisits();
        $this->registerJobs();
        $this->registerBase();
        $this->registerToAppConfig();
        $this->registerMiddleware();
    }

    /**
     * Prepare the package resources.
     *
     * @return void
     */
    protected function prepareResources()
    {
        // Publish our views
        $this->publishes([
            __DIR__ .  '/views' => base_path("resources/views")
        ]);

        // Publish our lang
        $this->publishes([
            __DIR__ .  '/lang' => base_path("resources/lang")
        ]);

        // Publish our public
        $this->publishes([
            __DIR__ .  '/public' => base_path("public")
        ]);

        // Publish our migrations
        $this->publishes([
            __DIR__ .  '/migrations' => base_path("database/migrations")
        ], 'migrations');

        // Publish our migrations
        $this->publishes([
            __DIR__ .  '/seeds' => base_path("database/seeds")
        ], 'seeds');

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
     * Registers the logger.
     *
     * @return void
     */
    protected function registerLog()
    {
        $this->app->singleton('jlourenco.log', function ($app) {
            $config = $app['config']->get('jlourenco.base');

            $model = array_get($config, 'models.Logs');

            return new LogRepository($model);
        });
    }

    /**
     * Registers the visits.
     *
     * @return void
     */
    protected function registerVisits()
    {
        $this->app->singleton('jlourenco.visits', function ($app) {
            $config = $app['config']->get('jlourenco.base');

            $model = array_get($config, 'models.Visits');

            return new VisitsRepository($model);
        });
    }

    /**
     * Registers the visits.
     *
     * @return void
     */
    protected function registerJobs()
    {
        $this->app->singleton('jlourenco.jobs', function ($app) {
            $config = $app['config']->get('jlourenco.base');

            $model = array_get($config, 'models.Jobs');

            return new JobsRepository($model);
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
            $base = new Base($app['jlourenco.settings'], $app['jlourenco.user'], $app['jlourenco.log'], $app['jlourenco.visits'], $app['jlourenco.jobs']);

            return $base;
        });

        $this->app->alias('base', 'jlourenco\base\Base');
    }

    /**
     * Registers this module to the
     * services providers and aliases.
     *
     * @return void
     */
    protected function registerToAppConfig()
    {
        /*
         * Register the service provider for the dependencies.
         */
        $this->app->register(\Cartalyst\Sentinel\Laravel\SentinelServiceProvider::class);
        $this->app->register(\TomLingham\Searchy\SearchyServiceProvider::class);
        $this->app->register(\Jenssegers\Agent\AgentServiceProvider::class);
        $this->app->register(\Torann\GeoIP\GeoIPServiceProvider::class);

        /*
         * Create aliases for the dependencies.
         */
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('Schema', 'jlourenco\support\Database\Schema');
        $loader->alias('Activation', 'Cartalyst\Sentinel\Laravel\Facades\Activation');
        $loader->alias('Reminder', 'Cartalyst\Sentinel\Laravel\Facades\Reminder');
        $loader->alias('Sentinel', 'Cartalyst\Sentinel\Laravel\Facades\Sentinel');
        $loader->alias('Datatables', 'yajra\Datatables\Datatables');
        $loader->alias('SentinelUser', 'App\Http\Middleware\SentinelUser');
        $loader->alias('Base', 'jlourenco\base\Facades\Base');
        $loader->alias('Searchy', 'TomLingham\Searchy\Facades\Searchy');
        $loader->alias('Agent', 'Jenssegers\Agent\Facades\Agent');
        $loader->alias('GeoIP', 'Torann\GeoIP\GeoIPFacade');
    }

    protected function registerMiddleware()
    {
        $config = $this->app['config']->get('jlourenco.base');
        $useMiddleware = array_get($config, 'UseDefaultMiddleware');
        $useMiddlewareGroup = array_get($config, 'UseDefaultMiddlewareGroups');

        if ($useMiddleware == true)
        {
            $this->app['router']->middleware('visits.counter', \jlourenco\base\Middleware\VisitsCounter::class);
            $this->app['router']->middleware('auth', \jlourenco\base\Middleware\SentinelUser::class);
        }

        if ($useMiddlewareGroup == true)
        {
            $this->app['router']->middlewareGroup('webAdmin', [
                \App\Http\Middleware\EncryptCookies::class,
                \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
                \Illuminate\Session\Middleware\StartSession::class,
                \Illuminate\View\Middleware\ShareErrorsFromSession::class,
                \App\Http\Middleware\VerifyCsrfToken::class,
                \jlourenco\base\Middleware\NewPasswordForce::class,
                'visits.counter',
                'throttle:30,1',
            ]);
            $this->app['router']->middlewareGroup('webPublic', [
                \App\Http\Middleware\EncryptCookies::class,
                \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
                \Illuminate\Session\Middleware\StartSession::class,
                \Illuminate\View\Middleware\ShareErrorsFromSession::class,
                \App\Http\Middleware\VerifyCsrfToken::class,
                \jlourenco\base\Middleware\NewPasswordForce::class,
                'visits.counter',
                'throttle:60,1',
            ]);
        }

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
            'jlourenco.log',
            'jlourenco.visits',
            'jlourenco.jobs',
            'base'
        ];
    }

}