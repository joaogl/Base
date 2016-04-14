<?php
return [
    /*
    |--------------------------------------------------------------------------
    | Base Package
    |--------------------------------------------------------------------------
    |
    | Please provide the models used in jlourenco package.
    |
    */

    'base' => [

        'models' => [

            'User' => 'jlourenco\base\Models\BaseUser',
            'Group' => 'jlourenco\base\Models\Group',
            'ActivityFeed' => 'jlourenco\base\Models\ActivityFeed',
            'Logs' => 'jlourenco\base\Models\Logs',
            'Visits' => 'jlourenco\base\Models\Visits',
            'Settings' => 'jlourenco\base\Models\Settings',

        ],
        'VisitCounter' => true,
    ],

];