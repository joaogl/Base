<?php namespace jlourenco\base\Models;

use Illuminate\Database\Eloquent\Model;
use jlourenco\base\Repositories\LogRepositoryInterface;
use jlourenco\support\Traits\Creation;

class Jobs extends Model
{

    /**
     * {@inheritDoc}
     */
    protected $table = 'jobs';

}