<?php namespace jlourenco\base\Traits;

trait Creation {

    /**
     * Boot the creation trait for a model.
     *
     * @return void
     */
    public static function bootCreation()
    {

        // create a event to happen on updating
        static::updating(function($table)  {
            $table->updated_by = Sentinel::getUser()->id;
        });

        // create a event to happen on deleting
        static::deleting(function($table)  {
            $table->deleted_by = Sentinel::getUser()->id;
        });

        // create a event to happen on saving
        static::saving(function($table)  {
            $table->created_by = Sentinel::getUser()->id;
        });

    }

}