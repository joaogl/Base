<?php namespace jlourenco\base\Models;

use Cartalyst\Sentinel\Roles\EloquentRole;
use Illuminate\Database\Eloquent\SoftDeletes;
use jlourenco\support\Traits\Creation;

class Group extends EloquentRole
{

    /**
     * {@inheritDoc}
     */
    protected $table = 'Group';

    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'name',
        'slug',
        'permissions',
        'description',
    ];

    /**
     * To allow soft deletes
     */
    use SoftDeletes;

    /**
     * To allow user actions identity (Created_by, Updated_by, Deleted_by)
     */
    use Creation;

    /**
     * The Users relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(static::$usersModel, 'Group_User', 'group', 'user')->withTimestamps();
    }

}
