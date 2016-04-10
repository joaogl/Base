<?php namespace jlourenco\base\Models;

use Illuminate\Database\Eloquent\Model;
use jlourenco\support\Traits\Creation;

class Settings extends Model
{

    /**
     * To allow user actions identity (Created_by, Updated_by, Deleted_by)
     */
    use Creation;

    /**
     * {@inheritDoc}
     */
    protected $table = 'Settings';

    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'value'
    ];

    public function options()
    {
        $str = explode("* ", $this->description);
        $choices = array();

        foreach($str as $st)
        {
            preg_match('/(?P<digit>\d+) - (?P<name>\w+)/', $st, $matches);
            if (sizeof($matches) > 0) {
                $st = str_replace("*/","",$st);
                $st = str_replace("/*","",$st);
                $choices[$matches[1]] = explode($matches[1] . ' - ', $st)[1];
            }
        }

        return $choices;
    }

}
