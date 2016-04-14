<?php namespace jlourenco\base\Models;

use Illuminate\Database\Eloquent\Model;
use jlourenco\base\Repositories\LogRepositoryInterface;
use jlourenco\support\Traits\Creation;
use Sentinel;

class Logs extends Model
{

    /**
     * To allow user actions identity (Created_by, Updated_by, Deleted_by)
     */
    use Creation;

    /**
     * {@inheritDoc}
     */
    protected $table = 'Logs';

    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'log',
        'target',
        'ip',
    ];

    /**
     * Get the user's first name.
     *
     * @param  string  $value
     * @return string
     */
    public function getCreatedByAttribute($value)
    {
        if ($value > 0)
            if ($user = Sentinel::findUserById($value))
                if ($user != null)
                    return $user->first_name . ' ' . $user->last_name . ' (ID: ' . $user->id . ')';

        return $value;
    }

    /**
     * Get the user's first name.
     *
     * @param  string  $value
     * @return string
     */
    public function getTargetAttribute($value)
    {
        if ($value > 0)
            if ($user = Sentinel::findUserById($value))
                if ($user != null)
                    return $user->first_name . ' ' . $user->last_name . ' (ID: ' . $user->id . ')';

        return $value;
    }

}