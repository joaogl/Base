<?php namespace jlourenco\base\Repositories;

interface SettingsRepositoryInterface
{

    /**
     * Finds a setting by the given primary key.
     *
     * @param  int  $id
     * @return \jlourenco\base\Models\Settings
     */
    public function findById($id);

    /**
     * Finds a setting by the given name.
     *
     * @param  string  $name
     * @return \jlourenco\base\Models\Settings
     */
    public function findByName($name);

    /**
     * Finds a setting by the given friendly name.
     *
     * @param  setting  $name
     * @return \jlourenco\base\Models\Settings
     */
    public function findByFriendlyName($name);

    /**
     * Returns the value for the requested setting
     *
     * @param  setting  $name
     * @return string
     */
    public function getSetting($name);

}
