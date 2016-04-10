<?php namespace jlourenco\base\Models;

use Illuminate\Database\Eloquent\Model;
use jlourenco\base\Repositories\LogRepositoryInterface;
use jlourenco\support\Traits\Creation;

class Visits extends Model
{

    /**
     * {@inheritDoc}
     */
    protected $table = 'Visits';

    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'ip',
        'url',
        'browser',
    ];

    public function getUnChecked()
    {
        return $this->where('checked', 0)
            ->get();
    }

}