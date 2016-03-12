<?php
/**
 * Frontend route group
 *
 * All the "restricted area" routes
 * are defined here.
 */
Route::group(array('prefix' => '/', 'middleware' => 'webPublic'), function ()
{

    /**
     * Basic related routes
     */

    # Dashboard
    Route::get('/', array('as' => 'home', function()
    {
        return View::make('home');
    }));

    Route::get('home', array('as' => 'home', function()
    {
        return View::make('home');
    }));

    /**
     * Account related routes
     */

    # All basic routes defined here
    Route::get('login', array('as' => 'login', 'uses' => 'jlourenco\base\Controllers\AuthController@getSignin'));
    Route::get('register', array('as' => 'register', 'uses' => 'jlourenco\base\Controllers\AuthController@getSignup'));
    Route::get('forgot-password', array('as' => 'forgot-password', 'uses' => 'jlourenco\base\Controllers\AuthController@getLostPassword'));

    Route::post('login','jlourenco\base\Controllers\AuthController@postSignin');
    Route::post('register',array('as' => 'register','uses' => 'jlourenco\base\Controllers\AuthController@postSignup'));
    Route::post('forgot-password',array('as' => 'forgot-password','uses' => 'jlourenco\base\Controllers\AuthController@postForgotPassword'));

    # Forgot Password Confirmation
    Route::get('forgot-password/{userId}/{passwordResetCode}', array('as' => 'forgot-password-confirm', 'uses' => 'jlourenco\base\Controllers\AuthController@getForgotPasswordConfirm'));
    Route::post('forgot-password/{userId}/{passwordResetCode}', 'jlourenco\base\Controllers\AuthController@postForgotPasswordConfirm');

    # Logout
    Route::get('logout', array('as' => 'logout','uses' => 'jlourenco\base\Controllers\AuthController@getLogout'));

    # Account Activation
    Route::get('activate/{userId}/{activationCode}', array('as' => 'activate', 'uses' => 'jlourenco\base\Controllers\AuthController@getActivate'));

    # User account
    Route::group(array('middleware' => 'SentinelUser'), function () {
        Route::get('my-account', array('as' => 'my-account', 'uses' => 'jlourenco\base\Controllers\UsersController@myAccount'));
        Route::post('my-account', 'jlourenco\base\Controllers\UsersController@updateAccount');
    });

});
