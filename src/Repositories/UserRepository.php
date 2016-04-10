<?php namespace jlourenco\base\Repositories;

use Cartalyst\Support\Traits\RepositoryTrait;

class UserRepository implements UserRepositoryInterface
{
    use RepositoryTrait;

    /**
     * The Settings model name.
     *
     * @var string
     */
    protected $model = 'jlourenco\base\Models\BaseUser';

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

}
