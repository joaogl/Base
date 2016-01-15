<?php namespace jlourenco\base\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Cartalyst\Sentinel\Users\EloquentUser;
use jlourenco\base\Traits\Creation;

class BaseUser extends EloquentUser
{

    /**
     * To allow soft deletes
     */
    use SoftDeletes;

    /**
     * To allow user actions identity
     */
    use Creation;

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
        'password',
        'last_name',
        'first_name',
        'permissions',
        'birthday',
        'status',
        'ip',
        'staff',
    ];

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
            'classes' => 'form-control input-lg JQCalendar',
            'maxlength' => 25,
            'save' => true
        ],
    ];

    protected $dates = ['birthday', 'last_login'];

    /**
     * Scope to get all the social contacts
     */
    private function scopeSocialLinks()
    {
        return $this->scopeContacts()->where('contact_reference', 'SocialNetworks');
    }

    /**
     * Scope do get all the contacts
     */
    private function scopeContacts()
    {
        return $this->belongsToMany('App\Contact', 'Contact_User', 'user', 'contact');
    }

    /**
     * Scope do get all the contacts
     */
    private function scopeProjects()
    {
        return $this->belongsToMany('App\Project', 'Project_User', 'user', 'project')->groupBy('Project_User.project');
    }

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

    /**
     * Method to get all the social contacts
     */
    public function socialLinks()
    {
        return $this->scopeSocialLinks()->take(5)->get();
    }

    /**
     * Method to get all the social contacts
     */
    public function projects()
    {
        return $this->scopeProjects()->take(5)->get();
    }

    /**
     * Method to get all the contacts.
     */
    public function contacts()
    {
        return $this->scopeContacts()->get();
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
        return $this->belongsToMany('jlourenco\base\Models\Group', 'Group_User', 'user', 'group')->withTimestamps();
    }

    /**
     * The Menus relationship.
     *
     * @return List of \App\Menu
     */
    public function menus($pos)
    {
        $results = $this->belongsToMany('App\Menu', 'Menu_User', 'user', 'menu')->withTimestamps()->where('pos', $pos)->get();

        foreach ($this->groups as $group)
            $results->merge($group->menus->where('pos', $pos));

        return $results;
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

}
