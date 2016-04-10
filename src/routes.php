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
    /* GET  */ Route::get('login', array('as' => 'login', 'uses' => 'jlourenco\base\Controllers\AuthController@getSignin'));
    /* POST */ Route::post('login', 'jlourenco\base\Controllers\AuthController@postSignin');

    /* GET  */ Route::get('register', array('as' => 'register', 'uses' => 'jlourenco\base\Controllers\AuthController@getSignup'));
    /* POST */ Route::post('register', array('as' => 'register','uses' => 'jlourenco\base\Controllers\AuthController@postSignup'));

    /* GET  */ Route::get('forgot-password', array('as' => 'forgot-password', 'uses' => 'jlourenco\base\Controllers\AuthController@getLostPassword'));
    /* POST */ Route::post('forgot-password', array('as' => 'forgot-password','uses' => 'jlourenco\base\Controllers\AuthController@postForgotPassword'));

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

    # Settings pages
    /* GET  */ Route::get('settings', 'jlourenco\base\Controllers\SettingsController@index');
    /* GET  */ Route::get('settings/{id}/edit', array('as' => 'settings.edit', 'uses' => 'jlourenco\base\Controllers\SettingsController@edit'));
    /* POST */ Route::post('settings/{id}/edit', array('as' => 'settings.update', 'uses' => 'jlourenco\base\Controllers\SettingsController@update'));

});
