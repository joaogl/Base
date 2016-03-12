<?php namespace jlourenco\base\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityFeed extends Model
{

    /**
     * {@inheritDoc}
     */
    protected $table = 'ActivityFeed';

    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'added_by',
        'activity',
        'icon',
        'link',
        'requirements',
    ];

}
