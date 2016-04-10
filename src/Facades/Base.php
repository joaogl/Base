<?php namespace jlourenco\base\Facades;

use Illuminate\Support\Facades\Facade;

class Base extends Facade
{

    /**
     * {@inheritDoc}
     */
    protected static function getFacadeAccessor()
    {
        return 'base';
    }

}
