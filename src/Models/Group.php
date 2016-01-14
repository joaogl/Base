<?php

namespace jlourenco\base\Models;

use Illuminate\Database\Eloquent\Model;
use Cartalyst\Sentinel\Permissions\PermissibleInterface;
use Cartalyst\Sentinel\Permissions\PermissibleTrait;

class Group extends \Cartalyst\Sentinel\Roles\EloquentRole
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

    /**
     * The Menus relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function menus()
    {
        return $this->belongsToMany('App\Menu', 'Menu_Group', 'group', 'menu')->withTimestamps();
    }

}
