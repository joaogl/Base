<?php namespace jlourenco\base\Repositories;

interface VisitsRepositoryInterface
{

    /**
     * Finds a visit by the given primary key.
     *
     * @param  int  $id
     * @return \jlourenco\base\Models\Settings
     */
    public function findById($id);

}
