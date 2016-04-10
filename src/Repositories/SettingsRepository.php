<?php namespace jlourenco\base\Repositories;

use Cartalyst\Support\Traits\RepositoryTrait;

class SettingsRepository implements SettingsRepositoryInterface
{
    use RepositoryTrait;

    /**
     * The Settings model name.
     *
     * @var string
     */
    protected $model = 'jlourenco\base\Models\Settings';

    /**
     * Create a new settings repository.
     *
     * @param  string  $model
     */
    public function __construct($model = null)
    {
        if (isset($model))
            $this->model = $model;
    }

    /**
     * {@inheritDoc}
     */
    public function findById($id)
    {
        return $this
            ->createModel()
            ->newQuery()
            ->find($id);
    }

    /**
     * {@inheritDoc}
     */
    public function findByName($name)
    {
        return $this
            ->createModel()
            ->newQuery()
            ->where('name', $name)
            ->first();
    }

    /**
     * {@inheritDoc}
     */
    public function findByFriendlyName($name)
    {
        return $this
            ->createModel()
            ->newQuery()
            ->where('friendly_name', $name)
            ->first();
    }

    public function getSetting($name)
    {
        return $this->findByFriendlyName($name)->value;
    }

}
