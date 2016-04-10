<?php namespace jlourenco\base\Repositories;

use Cartalyst\Support\Traits\RepositoryTrait;

class LogRepository implements LogRepositoryInterface
{
    use RepositoryTrait;

    /**
     * The Logs model name.
     *
     * @var string
     */
    protected $model = 'jlourenco\base\Models\Logs';

    /**
     * Create a new log repository.
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
