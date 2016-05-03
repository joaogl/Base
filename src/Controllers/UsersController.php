<?php namespace jlourenco\base\Controllers;

use App\Http\Controllers\Controller;
use Validator;
use App\Http\Requests;
use Sentinel;
use View;
use Input;
use File;
use Activation;
use Hash;
use Mail;
use URL;
use Illuminate\Support\Facades\Redirect;
use Base;
use DB;
use Lang;

class UsersController extends Controller
{

    /**
     * Declare the rules for the form validation
     *
     * @var array
     */
    protected $validationRules = array(
        'first_name'       => 'required|min:3',
        'last_name'        => 'required|min:3',
        'email'            => 'required|email|unique:User',
        'password'         => 'required|between:3,32',
        'password_confirm' => 'required|same:password',
        'pic'              => 'mimes:jpg,jpeg,bmp,png|max:10000'
    );

    protected $validationRulesAdmin = array(
        'gender'           =>  'required|digits_between:0,2',
        'first_name'       => 'required|min:3|max:25',
        'last_name'        => 'required|min:3|max:25',
        'password'         => 'required|between:3,32',
        'password_confirm' => 'required|same:password',
        'birthday'         =>  'date_format:d/m/Y|before:now',
    );

    protected $genders = [
        '0' => 'Male',
        '1' => 'Female',
        '2' => 'Other'
    ];

    protected $status = [
        '0' => 'Inactive',
        '1' => 'Active',
        '2' => 'Blocked',
        '3' => 'To create'
    ];

    /*
     * Public section
     */

    /**
     * Show a list of all the users.
     *
     * @return View
     */
    public function getIndex()
    {
        // Grab all the users
        $users = User::getAllStaff();

        // Show the page
        return View('collaborators', compact('users'));
    }

    /**
     * Display specified user profil.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(User $user)
    {
        // Show the page
        return View('collaborator', compact('user'));
    }

    /**
     * get user details and display
     */
    public function myAccount()
    {
        $user = Sentinel::getUser();

        return View::make('public.users.edit', compact('user'));
    }

    /**
     * update user details and display
     */
    public function updateAccount()
    {
        $user = Sentinel::getUser();

        //validationRules are declared at beginning
        $this->validationRules['email'] = "required|email|unique:users,email,{$user->email},email";

        if (!$password = Input::get('password')) {
            unset($this->validationRules['password']);
            unset($this->validationRules['password_confirm']);
        }

        $this->validationRules['birthday'] = 'date_format:d/m/Y|before:now';

        // Create a new validator instance from our validation rules
        $validator = Validator::make(Input::all(), $this->validationRules);

        // If validation fails, we'll exit the operation now.
        if ($validator->fails()) {
            // Ooops.. something went wrong
            return Redirect::back()->withInput()->withErrors($validator);
        }

        $email = $user->email;

        // Update the user
        $user->first_name = Input::get('first_name');
        $user->last_name = Input::get('last_name');
        $user->email = Input::get('email');
        $user->gender = Input::get('gender');
        $user->description = Input::get('description');

        if (Input::get('birthday') != null)
            $user->birthday = \Carbon\Carbon::createFromFormat('d/m/Y', Input::get('birthday'));

        $passwordChanged = false;

        // Do we want to update the user password?
        if ($password = Input::get('password'))
        {
            if (Sentinel::validateCredentials($user, [ 'email' => $email, 'password' => Input::get('old-password')]))
            {
                $passwordChanged = true;
                $user->password = Hash::make($password);
            }
            else
            {
                $error = Lang::get('base.auth.wrong_password');
                $validator->messages()->add('old-password', Lang::get('base.auth.wrong_password'));

                // Redirect to the user page
                return Redirect::route('profile')->withInput()->withErrors($validator)->with('error', $error);
            }
        }

        // is new image uploaded?
        if ($file = Input::file('pic')) {
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $folderName = '/uploads/users/';
            $destinationPath = public_path() . $folderName;
            $safeName = str_random(10) . '.' . $extension;
            $file->move($destinationPath, $safeName);

            //delete old pic if exists
            if (File::exists(public_path() . $folderName . $user->pic))
                File::delete(public_path() . $folderName . $user->pic);

            //save new file path into db
            $user->pic = $safeName;

            Base::Log($user->username . ' (' . $user->first_name . ' ' . $user->last_name . ') changed its profile photo. ');
        }

        // Was the user updated?
        if ($user->save()) {
            // Prepare the success message
            $success = Lang::get('base.auth.account.changed');

            if ($passwordChanged)
            {
                Base::Log($user->username . ' (' . $user->first_name . ' ' . $user->last_name . ') changed its password. ');
                Mail::queue('emails.account.password-changed', [ 'user' => $user ], function ($m) use ($user) {
                    $m->to($user->email, $user->first_name . ' ' . $user->last_name);
                    $m->subject(Lang::get('base.mails.password_changed'));
                });
            }

            Base::Log($user->username . ' (' . $user->first_name . ' ' . $user->last_name . ') updated the profile. ');

            // Redirect to the user page
            return Redirect::route('profile')->with('success', $success);
        }

        // Prepare the error message
        $error = Lang::get('base.base.error');

        // Redirect to the user page
        return Redirect::route('profile')->withInput()->with('error', $error);
    }

    /**
     * Show password change form
     */
    public function getChangePassword()
    {
        return View::make('public.users.change_password');
    }

    /**
     * Change password form processing page.
     *
     * @param  int      $id
     * @return Redirect
     */
    public function postChangePassword()
    {
        $user = Sentinel::getUser();

        $validation = array(
            'password'         => 'required|between:3,32',
            'password_confirm' => 'required|same:password',
        );

        // Create a new validator instance from our validation rules
        $validator = Validator::make(Input::all(), $validation);

        // If validation fails, we'll exit the operation now.
        if ($validator->fails()) {
            // Ooops.. something went wrong
            return Redirect::back()->withInput()->withErrors($validator);
        }

        try {
            // Do we want to update the user password?
            $password = Input::get('password');

            if (Sentinel::validateCredentials($user, [ 'email' => $user->email, 'password' => Input::get('old-password')]))
            {
                $user->password = Hash::make($password);
                $redirect = 'change-password';

                if ($user->force_new_password)
                {
                    $user->force_new_password = 0;
                    $redirect = 'home';
                }

                // Was the user updated?
                if ($user->save())
                {
                    // Prepare the success message
                    $success = Lang::get('base.auth.account.changed');

                    Mail::queue('emails.account.password-changed', [ 'user' => $user ], function ($m) use ($user) {
                        $m->to($user->email, $user->first_name . ' ' . $user->last_name);
                        $m->subject(Lang::get('base.mails.password_changed'));
                    });

                    Base::Log($user->username . ' (' . $user->first_name . ' ' . $user->last_name . ') changed its password account. ');

                    // Redirect to the user page
                    return Redirect::route($redirect)->with('success', $success);
                }
            }
            else
            {
                $error = Lang::get('base.auth.wrong_password');
                $validator->messages()->add('old-password', Lang::get('base.auth.wrong_password'));

                // Redirect to the user page
                return Redirect::route('change-password')->withInput()->withErrors($validator)->with('error', $error);
            }

            // Prepare the error message
        } catch (Exception $e) {
        }
        $error = Lang::get('base.base.error');


        // Redirect to the user page
        return Redirect::route('change-password')->withInput()->with('error', $error);
    }

    /*
     * Admin section
     */
    public function getAdminIndex()
    {
        // Grab all the users
        $users = Sentinel::createModel()->where('status', '=', '1')->Get();

        $possibleStatus = $this->status;
        $pending = false;

        // Show the page
        return View('admin.users.list', compact('users', 'possibleStatus', 'pending'));
    }

    public function getAdminPending()
    {
        // Grab all the users
        $users = Sentinel::createModel()->where('last_login', '=', null)->where('status', '=', '0')->Get();

        $possibleStatus = $this->status;
        $pending = true;

        // Show the page
        return View('admin.users.list', compact('users', 'possibleStatus', 'pending'));
    }

    public function getAdminBlocked()
    {
        // Grab all the users
        $users = Sentinel::createModel()->where('status', '=', '2')->Get();

        $possibleStatus = $this->status;
        $pending = true;

        // Show the page
        return View('admin.users.list', compact('users', 'possibleStatus', 'pending'));
    }

    /**
     * User update form processing page.
     *
     * @param  int      $id
     * @return Redirect
     */
    public function adminAccept($id = null)
    {
        // Get the user information
        $user = Sentinel::findById($id);

        if ($user == null || $user->last_login != null || $user->status != 0)
        {
            // Prepare the error message
            $error = Lang::get('base.auth.not_found');

            // Redirect to the user management page
            return Redirect::route('users.pending')->with('error', $error);
        }

        $user->status = 1;

        if ($user->save())
        {
            $activation = Activation::exists($user);

            if (!$activation)
            {
                Activation::create($user);

                $activation = Activation::exists($user);
            }

            if($activation)
                Activation::complete($user, $activation->code);

            Base::TargettedLog($user->username . ' (' . $user->first_name . ' ' . $user->last_name . ') account was accepted. ', $user->id);

            Mail::queue('emails.account.accepted-by-admin', [ 'user' => $user ], function ($m) use ($user) {
                $m->to($user->email, $user->first_name . ' ' . $user->last_name);
                $m->subject(Lang::get('base.mails.account_accepted'));
            });

            $success = 'User registration was accepted.';

            // Redirect to the user page
            return Redirect::route('users.pending')->withInput()->with('success', $success);
        }

        $error = Lang::get('base.base.error');

        // Redirect to the user page
        return Redirect::route('users.pending')->withInput()->with('error', $error);
    }

    /**
     * User update form processing page.
     *
     * @param  int      $id
     * @return Redirect
     */
    public function adminRefuse($id = null)
    {
        // Get the user information
        $user = Sentinel::findById($id);

        if ($user == null || $user->last_login != null || $user->status != 0)
        {
            // Prepare the error message
            $error = Lang::get('base.auth.not_found');

            // Redirect to the user management page
            return Redirect::route('users.pending')->with('error', $error);
        }

        $user->status = 2;

        if ($user->save())
        {
            Base::TargettedLog($user->username . ' (' . $user->first_name . ' ' . $user->last_name . ') account was refused. ', $user->id);

            if (Base::getSetting('SEND_EMAIL_ON_REFUSE'))
                Mail::queue('emails.account.refused-by-admin', [ 'user' => $user ], function ($m) use ($user) {
                    $m->to($user->email, $user->first_name . ' ' . $user->last_name);
                    $m->subject(Lang::get('base.mails.account_accepted'));
                });

            $success = Lang::get('base.auth.account.rejected');

            // Redirect to the user page
            return Redirect::route('users.pending')->withInput()->with('success', $success);
        }

        $error = Lang::get('base.base.error');

        // Redirect to the user page
        return Redirect::route('users.pending')->withInput()->with('error', $error);
    }

    /**
     * Display specified user profile.
     *
     * @param  int  $id
     * @return Response
     */
    public function adminShow($id)
    {
        // Get the user information
        $user = Sentinel::findUserById($id);

        if ($user == null)
        {
            // Prepare the error message
            $error = Lang::get('base.auth.not_found');

            // Redirect to the user management page
            return Redirect::route('users')->with('error', $error);
        }

        $possibleStatus = $this->status;

        $logs = Base::getLogsRepository()->where('created_by', $user->id)->orWhere('target', $user->id)->orderBy('created_at', 'desc')->take(300)->get(['ip', 'log', 'created_at', 'created_by', 'target']);
        $ips = Base::getLogsRepository()->where('created_by', $user->id)->where('log', 'LIKE', '%logged%')->orderBy('created_at', 'desc')->select('ip', DB::raw('count(*) as counter'), DB::raw('(SELECT created_at FROM Logs WHERE IP=ip ORDER BY created_at DESC LIMIT 1 ) as created_at'))->groupBy('ip')->take(300)->get();

        // Show the page
        return View('admin.users.show', compact('user', 'possibleStatus', 'logs', 'ips'));
    }

    /**
     * Show a list of all the deleted users.
     *
     * @return View
     */
    public function getAdminDeletedUsers()
    {
        // Grab deleted users
        $users = Sentinel::createModel()->onlyTrashed()->get();

        // Show the page
        return View('admin.users.deleted', compact('users'));
    }

    /**
     * Delete Confirm
     *
     * @param   int   $id
     * @return  View
     */
    public function getAdminModalDelete($id = null)
    {
        $confirm_route = $error = null;

        $title = 'Delete User';
        $message = 'Are you sure to delete this user?';

        // Get user information
        $user = Sentinel::findById($id);

        if ($user == null)
        {
            // Prepare the error message
            $error = Lang::get('base.auth.not_found');
            return View('layouts.modal_confirmation', compact('title', 'message', 'error', 'model', 'confirm_route'));
        }

        // Check if we are not trying to delete ourselves
        if ($user->id === Sentinel::getUser()->id)  {
            // Prepare the error message
            $error = Lang::get('base.base.error');

            return View('layouts.modal_confirmation', compact('title', 'message', 'error', 'model', 'confirm_route'));
        }

        $confirm_route = route('delete/user', ['id' => $user->id]);
        return View('layouts.modal_confirmation', compact('title', 'message', 'error', 'model', 'confirm_route'));
    }

    /**
     * Delete the given user.
     *
     * @param  int      $id
     * @return Redirect
     */
    public function getAdminDelete($id = null)
    {
        // Get user information
        $user = Sentinel::findById($id);

        if ($user == null)
        {
            // Prepare the error message
            $error = Lang::get('base.auth.not_found');

            // Redirect to the user management page
            return Redirect::route('users')->with('error', $error);
        }

        // Check if we are not trying to delete ourselves
        if ($user->id === Sentinel::getUser()->id) {
            // Prepare the error message
            $error = Lang::get('base.base.error');

            // Redirect to the user management page
            return Redirect::route('users')->with('error', $error);
        }

        // Delete the user
        //to allow soft deleted, we are performing query on users model instead of Sentinel model
        //$user->delete();
        Sentinel::createModel()->destroy($id);

        // Prepare the success message
        $success = Lang::get('base.auth.account.deleted');

        // Redirect to the user management page
        return Redirect::route('users')->with('success', $success);
    }

    /**
     * Restore a deleted user.
     *
     * @param  int      $id
     * @return Redirect
     */
    public function getAdminRestore($id = null)
    {
        // Get user information
        $user = Sentinel::createModel()->withTrashed()->find($id);

        if ($user == null)
        {
            // Prepare the error message
            $error = Lang::get('base.auth.not_found');

            // Redirect to the user management page
            return Redirect::route('users.deleted')->with('error', $error);
        }

        // Restore the user
        $user->restore();

        // Prepare the success message
        $success = Lang::get('base.auth.account.restored');

        // Redirect to the user management page
        return Redirect::route('users.deleted')->with('success', $success);
    }

    /**
     * User update.
     *
     * @param  int  $id
     * @return View
     */
    public function getAdminEdit($id = null)
    {
        // Get the user information
        if($user = Sentinel::findById($id))
        {
            // Get this user groups
            $userRoles = $user->getRoles()->lists('name', 'id')->all();

            // Get a list of all the available groups
            $roles = Sentinel::getRoleRepository()->all();
        }
        else
        {
            // Prepare the error message
            $error = Lang::get('base.auth.not_found');

            // Redirect to the user management page
            return Redirect::route('users')->with('error', $error);
        }

        $status = $user->status;
        $genders = $this->genders;
        $statusList = $this->status;

        $groups = null;
        $groups2 = Sentinel::getRoleRepository()->all(['id', 'name']);

        foreach ($groups2 as $g)
        {
            $has = false;
            foreach ($user->roles as $g2)
                if ($g2->id == $g->id)
                    $has = true;

            if (!$has)
                $groups[$g->id] = $g->name;
        }

        // Show the page
        return View('admin/users/edit', compact('user', 'status', 'genders', 'statusList', 'groups'));
    }

    /**
     * User update form processing page.
     *
     * @param  int      $id
     * @return Redirect
     */
    public function postAdminEdit($id = null)
    {
        // Get the user information
        $user = Sentinel::findById($id);

        if ($user == null)
        {
            // Prepare the error message
            $error = Lang::get('base.auth.not_found');

            // Redirect to the user management page
            return Redirect::route('admin.users.show')->with('error', $error);
        }

        $this->validationRulesAdmin['email'] = "required|email|unique:User,email,{$user->email},email,status,3|max:255";
        $this->validationRulesAdmin['username'] = "required|min:3|unique:User,username,{$user->username},username|max:25";

        // Do we want to update the user password?
        if (!$password = Input::get('password')) {
            unset($this->validationRulesAdmin['password']);
            unset($this->validationRulesAdmin['password_confirm']);
        }

        // Create a new validator instance from our validation rules
        $validator = Validator::make(Input::all(), $this->validationRulesAdmin);

        // If validation fails, we'll exit the operation now.
        if ($validator->fails()) {
            // Ooops.. something went wrong
            return Redirect::back()->withInput()->withErrors($validator);
        }

        try {
            // Update the user
            $user->gender   = Input::get('gender');
            $user->first_name  = Input::get('first_name');
            $user->last_name   = Input::get('last_name');
            $user->username    = Input::get('username');
            $user->email       = Input::get('email');
            $user->description = Input::get('description');

            if (Input::get('force_new_password'))
                $user->force_new_password = 1;
            else
                $user->force_new_password = 0;

            if (Input::get('birthday') != null)
                $user->birthday = \Carbon\Carbon::createFromFormat('d/m/Y', Input::get('birthday'));

            $password_changed = false;

            // Do we want to update the user password?
            if ($password)
            {
                $user->password = Hash::make($password);
                $password_changed = true;
            }

            // is new image uploaded?
            if ($file = Input::file('pic'))
            {
                $fileName        = $file->getClientOriginalName();
                $extension       = $file->getClientOriginalExtension() ?: 'png';

                if ($extension == 'png' || $extension == 'PNG' || $extension == 'JGP' || $extension == 'jpg' || $extension == 'gif')
                {
                    $folderName      = '/uploads/users/';
                    $destinationPath = public_path() . $folderName;
                    $safeName        = str_random(10).'.'.$extension;
                    $file->move($destinationPath, $safeName);

                    //delete old pic if exists
                    if(File::exists(public_path() . $folderName . $user->pic))
                        File::delete(public_path() . $folderName . $user->pic);

                    //save new file path into db
                    $user->pic   = $safeName;
                }
            }

            /*
            // Get the current user groups
            $userRoles = $user->roles()->lists('id')->all();

            // Get the selected groups
            $selectedRoles = Input::get('groups', array());

            // Groups comparison between the groups the user currently
            // have and the groups the user wish to have.
            $rolesToAdd    = array_diff($selectedRoles, $userRoles);
            $rolesToRemove = array_diff($userRoles, $selectedRoles);

            // Assign the user to groups
            foreach ($rolesToAdd as $roleId) {
                $role = Sentinel::findRoleById($roleId);

                $role->users()->attach($user);
            }

            // Remove the user from groups
            foreach ($rolesToRemove as $roleId) {
                $role = Sentinel::findRoleById($roleId);

                $role->users()->detach($user);
            }
            */

            // Activate / De-activate user
            $status = $activation = Activation::completed($user);
            $currentStatus = Input::get('status');

            if($currentStatus != $status)
            {
                if ($currentStatus == 0)
                    // Remove existing activation record
                    Activation::remove($user);
                else
                {
                    $activation = Activation::exists($user);

                    if (!$activation)
                    {
                        Activation::create($user);

                        $activation = Activation::exists($user);
                    }

                    if($activation)
                        Activation::complete($user, $activation->code);
                }

                $user->status = $currentStatus;
            }
            else
                $user->status = $currentStatus;

            // Was the user updated?
            if ($user->save())
            {
                if ($password_changed && Input::get('send_new_password_email'))
                {
                    Mail::queue('emails.account.password-changed-by-admin', [ 'user' => $user, 'new_password' => $password ], function ($m) use ($user) {
                        $m->to($user->email, $user->first_name . ' ' . $user->last_name);
                        $m->subject(Lang::get('base.mails.password_changed'));
                    });
                }

                if ($password_changed)
                    Base::TargettedLog($user->username . ' (' . $user->first_name . ' ' . $user->last_name . ') password was changed by an admin. ', $user->id);

                Base::TargettedLog($user->username . ' (' . $user->first_name . ' ' . $user->last_name . ') profile was changed by an admin. ', $user->id);

                // Prepare the success message
                $success = Lang::get('base.auth.user_changed');

                // Redirect to the user page
                return Redirect::route('users.update', $id)->with('success', $success);
            }

        } catch (Exception $e) {
        }
        $error = Lang::get('base.base.error');

        // Redirect to the user page
        return Redirect::route('users.update', $id)->withInput()->with('error', $error);
    }

    /**
     * Create new user
     *
     * @return View
     */
    public function getAdminCreate()
    {
        // Get all the available groups
        $groups = Sentinel::getRoleRepository()->all();

        $genders = $this->genders;
        $statusList = $this->status;
        $user = null;
        $status = 0;

        // Show the page
        return View('admin.users.create', compact('groups', 'genders', 'statusList', 'user', 'status'));
    }

    /**
     * User create form processing.
     *
     * @return Redirect
     */
    public function postAdminCreate()
    {
        $this->validationRulesAdmin['email'] = "required|email|unique:User";
        $this->validationRulesAdmin['username'] = "required|min:3|unique:User|max:25";
        $this->validationRulesAdmin['birthday'] = "required|date_format:d/m/Y|before:now";

        // Create a new validator instance from our validation rules
        $validator = Validator::make(Input::all(), $this->validationRulesAdmin);

        // If validation fails, we'll exit the operation now.
        if ($validator->fails()) {
            // Ooops.. something went wrong
            return Redirect::back()->withInput()->withErrors($validator);
        }

        //check whether use should be activated by default or not
        $activate = Input::get('status') != null && Input::get('status') != 0 ? true : false;

        try {
            $birthday = \Carbon\Carbon::createFromFormat('d/m/Y', Input::get('birthday'));

            // Register the user
            $user = Sentinel::register(array(
                'gender'   => Input::get('gender'),
                'first_name' => Input::get('first_name'),
                'last_name'  => Input::get('last_name'),
                'username'  => Input::get('username'),
                'birthday'   => $birthday,
                'email'      => Input::get('email'),
                'password'   => Input::get('password'),
                'status'   => Input::get('status'),
                //'pic'   => isset($safeName)?$safeName:'',
            ), $activate);

            $user->password = Hash::make(Input::get('password'));
            $user->description = Input::get('description');

            if (Input::get('force_new_password'))
                $user->force_new_password = 1;
            else
                $user->force_new_password = 0;

            // is new image uploaded?
            if ($file = Input::file('pic'))
            {
                $fileName        = $file->getClientOriginalName();
                $extension       = $file->getClientOriginalExtension() ?: 'png';

                if ($extension == 'png' || $extension == 'PNG' || $extension == 'JGP' || $extension == 'jpg' || $extension == 'gif')
                {
                    $folderName      = '/uploads/users/';
                    $destinationPath = public_path() . $folderName;
                    $safeName        = str_random(10).'.'.$extension;
                    $file->move($destinationPath, $safeName);

                    //delete old pic if exists
                    if(File::exists(public_path() . $folderName . $user->pic))
                        File::delete(public_path() . $folderName . $user->pic);

                    //save new file path into db
                    $user->pic   = $safeName;
                }
            }

            $user->save();

            Base::TargettedLog($user->username . ' (' . $user->first_name . ' ' . $user->last_name . ') account was created by an admin. ', $user->id);

            if (Input::get('send_new_password_email'))
            {
                if ($activate)
                {
                    Mail::queue('emails.account.account-created-by-admin', [ 'user' => $user, 'new_password' => Input::get('password') ], function ($m) use ($user) {
                        $m->to($user->email, $user->first_name . ' ' . $user->last_name);
                        $m->subject(Lang::get('base.mails.account_created'));
                    });
                }
                else
                {
                    Mail::queue('emails.account.account-created-by-admin-inactive', [ 'user' => $user, 'new_password' => Input::get('password') ], function ($m2) use ($user) {
                        $m2->to($user->email, $user->first_name . ' ' . $user->last_name);
                        $m2->subject(Lang::get('base.mails.account_created'));
                    });

                    $activation = Activation::create($user);

                    // Data to be used on the email view
                    $data = array(
                        'user'          => $user,
                        'activationUrl' => URL::route('activate', [$user->id, $activation->code]),
                    );

                    // Send the activation code through email
                    Mail::queue('emails.auth.register-activate', $data, function ($m) use ($user) {
                        $m->to($user->email, $user->first_name . ' ' . $user->last_name);
                        $m->subject(Lang::get('base.mails.welcome') . ' ' . $user->first_name);
                    });

                }
            }

            //add user to 'User' group
            /*$role = Sentinel::findRoleById(Input::get('group'));
            $role->users()->attach($user);

            //check for activation and send activation mail if not activated by default
            if(!Input::get('activate')) {
                // Data to be used on the email view
                $data = array(
                    'user'          => $user,
                    'activationUrl' => URL::route('activate', $user->id, Activation::create($user)->code),
                );

                // Send the activation code through email
                Mail::send('emails.register-activate', $data, function ($m) use ($user) {
                    $m->to($user->email, $user->first_name . ' ' . $user->last_name);
                    $m->subject('Welcome ' . $user->first_name);
                });
            }*/

            // Redirect to the home page with success menu
            return Redirect::route("users")->with('success', Lang::get('base.auth.account.created'));

        } catch (Exception $e) {
        }
        $error = Lang::get('base.base.error');

        // Redirect to the user creation page
        return Redirect::back()->withInput()->with('error', $error);
    }

    /**
     * Remove group Confirm
     *
     * @param   int   $id
     * @param   int   $gid
     * @return  View
     */
    public function getAdminModalRemoveGroup($id = null, $gid = null)
    {
        $confirm_route = $error = null;

        $title = 'Remove group';
        $message = 'Are you sure to remove this group from this user?';

        // Get user information
        $user = Sentinel::findById($id);

        if ($user == null)
        {
            // Prepare the error message
            $error = Lang::get('base.auth.not_found');
            return View('layouts.modal_confirmation', compact('title', 'message', 'error', 'model', 'confirm_route'));
        }

        // Check if we are not trying to delete ourselves
        if ($user->id === Sentinel::getUser()->id + 1)  {
            // Prepare the error message
            $error = Lang::get('base.base.yourself');

            return View('layouts.modal_confirmation', compact('title', 'message', 'error', 'model', 'confirm_route'));
        }

        // Get group information
        $group = Sentinel::findRoleById($gid);

        if ($group == null)
        {
            // Prepare the error message
            $error = Lang::get('base.groups.not_found');
            return View('layouts.modal_confirmation', compact('title', 'message', 'error', 'model', 'confirm_route'));
        }

        $confirm_route = route('remove/group', ['id' => $user->id, 'gid' => $group->id]);
        return View('layouts.modal_confirmation', compact('title', 'message', 'error', 'model', 'confirm_route'));
    }

    /**
     * Remove the group from the given user.
     *
     * @param  int      $id
     * @param  int      $gid
     * @return Redirect
     */
    public function getAdminRemoveGroup($id = null, $gid = null)
    {
        // Get user information
        $user = Sentinel::findById($id);

        if ($user == null)
        {
            // Prepare the error message
            $error = Lang::get('base.auth.not_found');
            return View('layouts.modal_confirmation', compact('title', 'message', 'error', 'model', 'confirm_route'));
        }

        // Check if we are not trying to delete ourselves
        if ($user->id === Sentinel::getUser()->id + 1)  {
            // Prepare the error message
            $error = Lang::get('base.base.yourself');

            return View('layouts.modal_confirmation', compact('title', 'message', 'error', 'model', 'confirm_route'));
        }

        // Get group information
        $group = Sentinel::findRoleById($gid);

        if ($group == null)
        {
            // Prepare the error message
            $error = Lang::get('base.groups.not_found');
            return View('layouts.modal_confirmation', compact('title', 'message', 'error', 'model', 'confirm_route'));
        }

        // Remove the group
        $group->users()->detach($user);

        // Prepare the success message
        $success = Lang::get('base.groups.removed');

        // Redirect to the user management page
        return Redirect::route('users.update', $user->id)->with('success', $success);
    }

    /**
     * Add the group to a given user.
     *
     * @param  int      $id
     * @return Redirect
     */
    public function postAdminAddGroup($id = null)
    {
        // Get user information
        $user = Sentinel::findById($id);

        if ($user == null)
        {
            // Prepare the error message
            $error = Lang::get('base.auth.not_found');
            return View('layouts.modal_confirmation', compact('title', 'message', 'error', 'model', 'confirm_route'));
        }

        $gid = Input::get('group');

        if ($gid == null)
        {
            // Prepare the error message
            $error = Lang::get('base.groups.not_found');
            return View('layouts.modal_confirmation', compact('title', 'message', 'error', 'model', 'confirm_route'));
        }

        // Get group information
        $group = Sentinel::findRoleById($gid);

        if ($group == null)
        {
            // Prepare the error message
            $error = Lang::get('base.groups.not_found');
            return View('layouts.modal_confirmation', compact('title', 'message', 'error', 'model', 'confirm_route'));
        }

        // Remove the group
        $group->users()->attach($user);

        // Prepare the success message
        $success = Lang::get('base.groups.added');

        // Redirect to the user management page
        return Redirect::route('users.update', $user->id)->with('success', $success);
    }

}
