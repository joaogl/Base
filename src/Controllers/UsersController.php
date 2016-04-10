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
use Illuminate\Support\Facades\Redirect;

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
        //'username'         => 'required|min:3|unique:User|max:25',
        'first_name'       => 'required|min:3|max:25',
        'last_name'        => 'required|min:3|max:25',
        //'email'            => 'required|email|unique:User,email,3,status|max:255',
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
        // Base::Log('Website settings changed. Changed "' . $move->friendly_name . '" value from "' . $before . '" to "' . $after . '"');
        // Mail::queue('emails.auth.password-changed', [ 'user' => $user], function ($m) use ($user) {
        //    $m->to($user->email, $user->first_name . ' ' . $user->last_name);
        //    $m->subject('Account Password Changed');
        //});

        
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

        // Do we want to update the user password?
        if ($password = Input::get('password'))
        {
            if (Sentinel::validateCredentials($user, [ 'email' => $email, 'password' => Input::get('old-password')]))
                $user->password = Hash::make($password);
            else
            {
                $error = "Incorrect password";
                $validator->messages()->add('old-password', 'Incorrect password');

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
        }

        // Was the user updated?
        if ($user->save()) {
            // Prepare the success message
            $success = 'User was successfully updated.';

            // Redirect to the user page
            return Redirect::route('profile')->with('success', $success);
        }

        // Prepare the error message
        $error = 'There was an issue updating the user. Please try again.';

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
                    $success = 'User was successfully updated.';

                    // Redirect to the user page
                    return Redirect::route($redirect)->with('success', $success);
                }
            }
            else
            {
                $error = "Incorrect password";
                $validator->messages()->add('old-password', 'Incorrect password');

                // Redirect to the user page
                return Redirect::route('change-password')->withInput()->withErrors($validator)->with('error', $error);
            }

            // Prepare the error message
            $error = 'There was an issue updating the user. Please try again.';
        } catch (LoginRequiredException $e) {
            $error = 'The login field is required';
        }

        // Redirect to the user page
        return Redirect::route('change-password')->withInput()->with('error', $error);
    }

    /*
     * Admin section
     */
    public function getAdminIndex()
    {
        // Grab all the users
        $users = Sentinel::createModel()->All();

        $possibleStatus = $this->status;

        // Show the page
        return View('admin.users.list', compact('users', 'possibleStatus'));
    }

    /**
     * Display specified user profile.
     *
     * @param  int  $id
     * @return Response
     */
    public function adminShow($id)
    {
        try {
            // Get the user information
            $user = Sentinel::findUserById($id);

        } catch (UserNotFoundException $e) {
            // Prepare the error message
            $error = 'User '. $id . ' does not exist.';

            // Redirect to the user management page
            return Redirect::route('users')->with('error', $error);
        }

        $possibleStatus = $this->status;

        // Show the page
        return View('admin.users.show', compact('user', 'possibleStatus'));

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

        try {
            // Get user information
            $user = Sentinel::findById($id);

            // Check if we are not trying to delete ourselves
            if ($user->id === Sentinel::getUser()->id)  {
                // Prepare the error message
                $error = "There was an issue deleting the user. Please try again.";

                return View('layouts.modal_confirmation', compact('error', 'model', 'confirm_route'));
            }
        } catch (UserNotFoundException $e) {
            // Prepare the error message
            $error = 'User '. $id . ' does not exist.';
            return View('layouts.modal_confirmation', compact('error', 'model', 'confirm_route'));
        }

        $confirm_route = route('delete/user', ['id' => $user->id]);
        return View('layouts.modal_confirmation', compact('error', 'model', 'confirm_route'));
    }

    /**
     * Delete the given user.
     *
     * @param  int      $id
     * @return Redirect
     */
    public function getAdminDelete($id = null)
    {
        try {
            // Get user information
            $user = Sentinel::findById($id);

            // Check if we are not trying to delete ourselves
            if ($user->id === Sentinel::getUser()->id) {
                // Prepare the error message
                $error = 'There was an issue deleting the user. Please try again.';

                // Redirect to the user management page
                return Redirect::route('users')->with('error', $error);
            }

            // Delete the user
            //to allow soft deleted, we are performing query on users model instead of Sentinel model
            //$user->delete();
            Sentinel::createModel()->destroy($id);

            // Prepare the success message
            $success = 'User was successfully deleted.';

            // Redirect to the user management page
            return Redirect::route('users')->with('success', $success);
        } catch (UserNotFoundException $e) {
            // Prepare the error message
            $error = 'User ' . $id . ' does not exist.';

            // Redirect to the user management page
            return Redirect::route('users')->with('error', $error);
        }
    }

    /**
     * Restore a deleted user.
     *
     * @param  int      $id
     * @return Redirect
     */
    public function getAdminRestore($id = null)
    {
        try {
            // Get user information
            $user = Sentinel::createModel()->withTrashed()->find($id);

            // Restore the user
            $user->restore();

            // Prepare the success message
            $success = 'User was successfully restored.';

            // Redirect to the user management page
            return Redirect::route('users.deleted')->with('success', $success);
        } catch (UserNotFoundException $e) {
            // Prepare the error message
            $error = 'User ' . $id . ' does not exist.';

            // Redirect to the user management page
            return Redirect::route('users.deleted')->with('error', $error);
        }
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
            $error = Lang::get('users/message.user_not_found', compact('id'));

            // Redirect to the user management page
            return Redirect::route('users')->with('error', $error);
        }

        $status = $user->status;
        $genders = $this->genders;
        $statusList = $this->status;

        // Show the page
        return View('admin/users/edit', compact('user', 'roles', 'userRoles', 'status', 'genders', 'statusList'));
    }

    /**
     * User update form processing page.
     *
     * @param  int      $id
     * @return Redirect
     */
    public function postAdminEdit($id = null)
    {
        try {
            // Get the user information
            $user = Sentinel::findById($id);
        } catch (UserNotFoundException $e) {
            // Prepare the error message
            $error = 'User ' . $id . ' does not exist.';

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

            // Was the user updated?
            if ($user->save())
            {
                if ($password_changed && Input::get('send_new_password_email'))
                {
                    // Send email!!!
                }

                // Prepare the success message
                $success = 'User was successfully updated.';

                // Redirect to the user page
                return Redirect::route('users.update', $id)->with('success', $success);
            }

            // Prepare the error message
            $error = 'There was an issue updating the user. Please try again.';
        } catch (LoginRequiredException $e) {
            $error = 'The login field is required';
        }

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
            return Redirect::route("users")->with('success', 'User was successfully deleted.');

        } catch (LoginRequiredException $e) {
            $error = 'The login field is required';
        } catch (PasswordRequiredException $e) {
            $error = 'The password is required.';
        } catch (UserExistsException $e) {
            $error = 'User already exists!\'';
        }

        // Redirect to the user creation page
        return Redirect::back()->withInput()->with('error', $error);
    }

}
