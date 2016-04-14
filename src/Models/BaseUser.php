<?php namespace jlourenco\base\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Cartalyst\Sentinel\Users\EloquentUser;
use jlourenco\support\Traits\Creation;
use Nicolaslopezj\Searchable\SearchableTrait;

class BaseUser extends EloquentUser
{

    /**
     * To allow soft deletes
     */
    use SoftDeletes;

    /**
     * To allow user actions identity (Created_by, Updated_by, Deleted_by)
     */
    use Creation;

    /**
     * To allow this model to be searched
     */
    use SearchableTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'User';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array('password', 'remember_token');

    /**
     * The attributes used to test the login against.
     *
     * @var array
     */
    protected $loginNames = ['username', 'email'];

    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'username',
        'email',
        //'password',
        'last_name',
        'first_name',
        'permissions',
        'birthday',
        'status',
        'ip',
        'staff',
        'gender',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['password'];

    /**
     * The attributes that will appear on the register form.
     *
     * @var array
     */
    protected $registerFields = [
        'first_name' => [
            'type' => 'text',
            'validator' => 'required|min:3|max:25',
            'label' => 'First name',
            'placeholder' => 'You first name',
            'classes' => 'form-control input-lg JQMaxLength',
            'maxlength' => 25,
            'save' => true
        ],
        'last_name' => [
            'type' => 'text',
            'validator' => 'required|min:3|max:25',
            'label' => 'Last name',
            'placeholder' => 'You last name',
            'classes' => 'form-control input-lg JQMaxLength',
            'maxlength' => 25,
            'save' => true
        ],
        'username' => [
            'type' => 'text',
            'validator' => 'required|min:3|unique:User|max:25',
            'label' => 'Username',
            'placeholder' => 'You username',
            'classes' => 'form-control input-lg JQMaxLength',
            'maxlength' => 25,
            'save' => true
        ],
        'email' => [
            'type' => 'text',
            'validator' => 'required|email|unique:User,email,3,status|max:255',
            'label' => 'Email',
            'placeholder' => 'Your email',
            'classes' => 'form-control input-lg JQMaxLength',
            'maxlength' => 255,
            'save' => true
        ],
        'email_confirm' => [
            'type' => 'text',
            'validator' => 'required|email|same:email',
            'label' => 'Confirm email',
            'placeholder' => 'Confirm your email',
            'classes' => 'form-control input-lg JQMaxLength',
            'maxlength' => 255,
            'save' => false
        ],
        'password' => [
            'type' => 'password',
            'validator' => 'required|between:3,32',
            'label' => 'Password',
            'placeholder' => 'You password',
            'classes' => 'form-control input-lg JQMaxLength',
            'maxlength' => 30,
            'save' => true
        ],
        'password_confirm' => [
            'type' => 'password',
            'validator' => 'required|same:password',
            'label' => 'Confirm password',
            'placeholder' => 'Confirm your password',
            'classes' => 'form-control input-lg JQMaxLength',
            'maxlength' => 30,
            'save' => false
        ],
        'birthday' => [
            'type' => 'text',
            'validator' => 'date_format:d/m/Y|before:now',
            'label' => 'Birthday',
            'placeholder' => 'Your birthday',
            'classes' => 'form-control input-lg JQCalendar datepicker',
            'maxlength' => 10,
            'save' => true
        ],
        'gender' => [
            'type' => 'gender',
            'validator' => 'required|digits_between:0,2',
            'label' => 'Gender',
            'placeholder' => 'Your gender',
            'classes' => 'form-control',
            'save' => true
        ],
    ];

    protected $dates = ['birthday', 'last_login', 'deleted_at'];

    /**
     * The groups model name.
     *
     * @var string
     */
    protected static $groupsModel = 'jlourenco\base\Models\Group';

    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
        'columns' => [
            'User.first_name' => 10,
            'User.last_name' => 10,
            'BlogPost.keywords' => 5,
            'BlogPost.title' => 3,
            'BlogPost.contents' => 1,
        ],
        'joins' => [
            'BlogPost' => [ 'User.id', 'BlogPost.author' ],
        ],
    ];

    /**
     * Scope do get all staff
     */
    private function scopeStaff()
    {
        return $this->where('staff', 1);
    }

    public static function getAllStaff()
    {
        return BaseUser::where('staff', 1)
            ->orderBy('first_name', 'desc')
            ->get();
    }

    public function status()
    {
        switch ($this->status) {
            case 0:
                return "<span class=\"label label-warning square\">Inactive</span>";
            case 1:
                return "<span class=\"label label-success square\">Active</span>";
            case 2:
                return "<span class=\"label label-danger square\">Blocked</span>";
            case 3:
                return "<span class=\"label label-warning square\">Inactive</span>";
        }
    }

    /**
     * Returns an array of register column fields.
     *
     * @return array
     */
    public function getRegisterFields()
    {
        return $this->registerFields;
    }

    /**
     * The Groups relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groups()
    {
        return $this->belongsToMany(static::$groupsModel, 'Group_User', 'user', 'group')->withTimestamps();
    }

    /**
     * Returns the roles relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(static::$rolesModel, 'Group_User', 'user', 'group')->withTimestamps();
    }

    /**
     * Returns the groups model.
     *
     * @return string
     */
    public static function getGroupsModel()
    {
        return static::$groupsModel;
    }

    /**
     * Sets the groups model.
     *
     * @param  string  $groupsModel
     * @return void
     */
    public static function setGroupsModel($groupsModel)
    {
        static::$groupsModel = $groupsModel;
    }

}
