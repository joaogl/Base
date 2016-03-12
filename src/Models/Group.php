<?php namespace jlourenco\base\Models;

use Cartalyst\Sentinel\Roles\EloquentRole;

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
     * The Users relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(static::$usersModel, 'Group_User', 'group', 'user')->withTimestamps();
    }

}
