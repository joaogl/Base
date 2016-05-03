<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | JLourenco Base Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during execution for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'auth' => array(

        'logged_out' => 'You have successfully logged out!',
        'wrong_password' => 'Incorrect password',
        'not_found' => 'User does not exist.',
        'user_changed' => 'User was successfully updated.',

        'forgot_password' => array(
            'error'   => 'There was a problem while trying to get a reset password code, please try again.',
            'success' => 'Password recovery email successfully sent.',
        ),

        'forgot_password_confirm' => array(
            'error'   => 'There was a problem while trying to reset your password, please try again.',
            'success' => 'Your password has been successfully reset.',
        ),

        'activate' => array(
            'error'   => 'There was a problem while trying to activate your account, please try again.',
            'success' => 'Your account has been successfully activated.',
        ),

        'signin' => array(
            'error'   => 'There was a problem while trying to log you in, please try again.',
            'success' => 'You have successfully logged in.',
        ),

        'signup' => array(
            'success' => 'You have successfully signed up.',
            'self' => 'You have received an email to activate your account.',
            'admin' => ' The administrator will review your registration and once approved you\'ll reveice an email.',
            'ready' => '',
        ),

        'account' => array(
            'not_found'      => 'Username or password is incorrect.',
            'not_activated'  => 'This user account is not activated.',
            'suspended'      => 'User account suspended because of too many login attempts. Try again after [:delay] seconds',
            'registration_disabled' => 'Registration is disabled at the moment',
            'registration_failed' => 'Something went wrong. Please try using a different email, if it still doesn\'t work please contact the administrator.',
            'already_exists' => 'An account with this email already exists.',
            'created' => 'Account successfully created.',
            'changed' => 'Your user was successfully updated.',
            'rejected' => 'User registration was refused.',
            'deleted' => 'User was successfully deleted.',
            'restored' => 'User was successfully restored.',
        ),

    ),

    'captcha' => array(
        'error'   => 'Wrong captcha',
    ),

    'mails' => array(
        'welcome'   => 'Welcome',
        'recovery' => 'Account Password Recovery',
        'password_changed' => 'Account Password Changed',
        'account_accepted' => 'Account Registration Accepted',
        'account_rejected' => 'Account Registration Refused',
        'account_created' => 'Account Created',
    ),

    'base' => array(
        'error'   => 'There was an issue updating the user. Please try again.',
        'yourself'   => 'You cannot edit yourself.',
    ),

    'groups' => array(
        'not_found' => 'Group does not exist.',
        'removed' => 'Group successfully removed.',
        'already_exists' => 'A group with this name already exists.',
        'created' => 'Group successfully created.',
        'deleted' => 'Group was successfully deleted.',
        'changed' => 'Group was successfully updated.',
        'added' => 'Group was successfully added.',
    ),

);
