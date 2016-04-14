<?php namespace jlourenco\base\Repositories;

interface JobsRepositoryInterface
{

    /**
     * Finds a setting by the given primary key.
     *
     * @param  int  $id
     * @return \jlourenco\base\Models\Settings
     */
    public function findById($id);

}
