<?php namespace jlourenco\base\Controllers;

use App\Http\Controllers\Controller;
use Validator;
use App\Http\Requests;
use Sentinel;

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
        'email'            => 'required|email|unique:users',
        'password'         => 'required|between:3,32',
        'password_confirm' => 'required|same:password',
        'pic'              => 'mimes:jpg,jpeg,bmp,png|max:10000'
    );

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

        return View::make('user_account', compact('user', 'countries'));
    }

    /**
     * update user details and display
     */
    public function updateAccount()
    {
        //$user = Sentinel::findById($id);
        $user = Sentinel::getUser();

        //validationRules are declared at beginning
        if (Input::get('email')) {
            $this->validationRules['email'] = "required|email|unique:users,email,{$user->email},email";
        } else {
            unset($this->validationRules['email']);
        }

        if (!$password = Input::get('password')) {
            unset($this->validationRules['password']);
            unset($this->validationRules['password_confirm']);
        }

        // Create a new validator instance from our validation rules
        $validator = Validator::make(Input::all(), $this->validationRules);

        // If validation fails, we'll exit the operation now.
        if ($validator->fails()) {
            // Ooops.. something went wrong
            return Redirect::back()->withInput()->withErrors($validator);
        }

        // Update the user
        $user->first_name = Input::get('first_name');
        $user->last_name = Input::get('last_name');
        $user->email = Input::get('email');
        $user->gender = Input::get('gender');
        $user->dob = Input::get('dob');
        $user->country = Input::get('country');
        $user->address = Input::get('address');
        $user->state = Input::get('state');
        $user->city = Input::get('city');
        $user->postal = Input::get('postal');

        // Do we want to update the user password?
        if ($password = Input::get('password')) {
            $user->password = Hash::make($password);
        }

        // is new image uploaded?
        if ($file = Input::file('pic')) {
            $fileName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $folderName = '/uploads/users/';
            $destinationPath = public_path() . $folderName;
            $safeName = str_random(10) . '.' . $extension;
            $file->move($destinationPath, $safeName);

            //delete old pic if exists
            if (File::exists(public_path() . $folderName . $user->pic)) {
                File::delete(public_path() . $folderName . $user->pic);
            }

            //save new file path into db
            $user->pic = $safeName;

        }

        // Was the user updated?
        if ($user->save()) {
            // Prepare the success message
            $success = Lang::get('users/message.success.update');

            // Redirect to the user page
            return Redirect::route('my-account')->with('success', $success);
        }

        // Prepare the error message
        $error = Lang::get('users/message.error.update');

        // Redirect to the user page
        return Redirect::route('my-account')->withInput()->with('error', $error);

    }

}
