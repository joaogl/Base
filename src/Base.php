<?php namespace jlourenco\base;

use jlourenco\base\Repositories\SettingsRepositoryInterface;
use jlourenco\base\Repositories\UserRepositoryInterface;
use BadMethodCallException;

class Base
{

    /**
     * The Settings repository.
     *
     * @var \jlourenco\base\Repositories\SettingsRepositoryInterface
     */
    protected $settings;

    /**
     * The User repository.
     *
     * @var \jlourenco\base\Repositories\UserRepositoryInterface
     */
    protected $user;

    /**
     * Cached, available methods on the settings repository, used for dynamic calls.
     *
     * @var array
     */
    protected $settingsMethods = [];

    /**
     * Create a new Base instance.
     *
     * @param  \jlourenco\base\Repositories\SettingsRepositoryInterface  $settings
     * @param  \jlourenco\base\Repositories\UserRepositoryInterface  $user
     */
    public function __construct(SettingsRepositoryInterface $settings, UserRepositoryInterface $user)
    {
        $this->settings = $settings;
        $this->user = $user;
    }

    /**
     * Returns the settings repository.
     *c
     * @return \jlourenco\base\Repositories\SettingsRepositoryInterface
     */
    public function getSettingsRepository()
    {
        return $this->settings;
    }

    /**
     * Sets the ettings repository.
     *
     * @param  \jlourenco\base\Repositories\SettingsRepositoryInterface $settings
     * @return void
     */
    public function setSettingsRepository(SettingsRepositoryInterface $settings)
    {
        $this->settings = $settings;
    }

    /**
     * Returns the ers repository.
     *c
     * @return \jlourenco\base\Repositories\UserRepositoryInterface
     */
    public function getUsersRepository()
    {
        return $this->user;
    }

    /**
     * Sets the ettings repository.
     *
     * @param  \jlourenco\base\Repositories\UserRepositoryInterface $user
     * @return void
     */
    public function setUsersRepository(UserRepositoryInterface $user)
    {
        $this->user = $user;
    }

    /**
     * Returns all accessible methods on the associated settings repository.
     *
     * @return array
     */
    protected function getSettingsMethods()
    {
        if (empty($this->settingsMethods)) {
            $settings = $this->getSettingsRepository();

            $methods = get_class_methods($settings);

            $this->settingsMethods = array_diff($methods, ['__construct']);
        }

        return $this->settingsMethods;
    }

    /**
     * Dynamically pass missing methods to Base.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     * @throws \BadMethodCallException
     */
    public function __call($method, $parameters)
    {
        $methods = $this->getSettingsMethods();

        if (in_array($method, $methods)) {
            $users = $this->getSettingsRepository();

            return call_user_func_array([$users, $method], $parameters);
        }

        throw new BadMethodCallException("Call to undefined method {$method}()");
    }

}
