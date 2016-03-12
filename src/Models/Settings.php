<?php namespace jlourenco\base\Models;

use Illuminate\Database\Eloquent\Model;
use jlourenco\support\Traits\Creation;

class Settings extends Model
{

    /**
     * To allow user actions identity (Created_by, Updated_by, Deleted_by)
     */
    use Creation;

    /**
     * {@inheritDoc}
     */
    protected $table = 'Settings';

    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'value'
    ];

}
