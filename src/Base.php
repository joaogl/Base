<?php namespace jlourenco\base;

use jlourenco\base\Repositories\SettingsRepositoryInterface;
use jlourenco\base\Repositories\UserRepositoryInterface;
use jlourenco\base\Repositories\LogRepositoryInterface;
use jlourenco\base\Repositories\VisitsRepositoryInterface;
use jlourenco\base\Repositories\JobsRepositoryInterface;
use BadMethodCallException;
use Request;
use Jenssegers\Agent\Agent;
use GeoIP;
use Sentinel;

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
     * The Log repository.
     *
     * @var \jlourenco\base\Repositories\LogRepositoryInterface
     */
    protected $logs;

    /**
     * The Job repository.
     *
     * @var \jlourenco\base\Repositories\JobsRepositoryInterface
     */
    protected $jobs;

    /**
     * The Visits repository.
     *
     * @var \jlourenco\base\Repositories\VisitsRepositoryInterface
     */
    protected $visits;

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
     * @param  \jlourenco\base\Repositories\LogRepositoryInterface  $log
     * @param  \jlourenco\base\Repositories\VisitsRepositoryInterface  $visits
     */
    public function __construct(SettingsRepositoryInterface $settings, UserRepositoryInterface $user, LogRepositoryInterface $log, VisitsRepositoryInterface $visits, JobsRepositoryInterface $jobs)
    {
        $this->settings = $settings;
        $this->user = $user;
        $this->logs = $log;
        $this->visits = $visits;
        $this->jobs = $jobs;
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
     * Returns the log repository.
     *c
     * @return \jlourenco\base\Repositories\LogRepositoryInterface
     */
    public function getLogsRepository()
    {
        return $this->logs;
    }

    /**
     * Sets the log repository.
     *
     * @param  \jlourenco\base\Repositories\LogRepositoryInterface $log
     * @return void
     */
    public function setLogsRepository(LogRepositoryInterface $log)
    {
        $this->logs = $log;
    }

    /**
     * Returns the visits repository.
     *c
     * @return \jlourenco\base\Repositories\VisitsRepositoryInterface
     */
    public function getVisitsRepository()
    {
        return $this->visits;
    }

    /**
     * Sets the visits repository.
     *
     * @param  \jlourenco\base\Repositories\VisitsRepositoryInterface $visits
     * @return void
     */
    public function setVisitsRepository(VisitsRepositoryInterface $visits)
    {
        $this->visits = $visits;
    }

    /**
     * Returns the jobs repository.
     *c
     * @return \jlourenco\base\Repositories\JobsRepositoryInterface
     */
    public function getJobsRepository()
    {
        return $this->jobs;
    }

    /**
     * Sets the jobs repository.
     *
     * @param  \jlourenco\base\Repositories\JobsRepositoryInterface $jobs
     * @return void
     */
    public function setJobsRepository(JobsRepositoryInterface $jobs)
    {
        $this->jobs = $jobs;
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

    /**
     * Create a log
     *
     * @param $logMessage
     */
    public function Log($logMessage)
    {
        $target = null;

        if ($user = Sentinel::getUser())
            if ($user != null)
                $target = $user->id;

        $log = $this->getLogsRepository()->create(['log' => $logMessage, 'ip' => Request::ip(), 'target' => $target]);
    }

    /**
     * Create a log and associates it to a user
     *
     * @param $logMessage
     * @param $userTarget
     */
    public function TargettedLog($logMessage, $userTarget)
    {
        $log = $this->getLogsRepository()->create(['log' => $logMessage, 'target' => $userTarget, 'ip' => Request::ip()]);
    }

    public function RegisterVisit()
    {
        $agent = new Agent();
        $browser = $agent->browser();
        $version = $agent->version($browser);

        $platform = $agent->platform();
        $pversion = $agent->version($platform);

        $browserString = $browser . ' version ' . $version . ' | ' . $platform . ' version ' . $pversion;


        $visit = $this->getVisitsRepository()->create(['url' => Request::url(), 'ip' => Request::ip(), 'browser' => $browserString]);
    }

    public function CompleteVisits()
    {
        $visits = $this->getVisitsRepository()->getUnChecked();

        foreach ($visits as $visit)
        {
            $location = GeoIP::getLocation($visit->ip);

            if (!$location['default'])
            {
                $visit->isoCode = $location['isoCode'];
                $visit->country = $location['country'];
                $visit->city = $location['city'];
                $visit->state = $location['state'];
                $visit->postal_code = $location['postal_code'];
                $visit->lat = $location['lat'];
                $visit->lon = $location['lon'];
                $visit->timezone = $location['timezone'];
                $visit->continent = $location['continent'];
            }

            $visit->checked = 1;
            $visit->save();
        }
    }

}
