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

    Route::get('home', array('as' => 'user.home', function()
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
    Route::get('logout', array('as' => 'logout', 'uses' => 'jlourenco\base\Controllers\AuthController@getLogout', 'middleware' => 'auth'));

    # Account Activation
    Route::get('activate/{userId}/{activationCode}', array('as' => 'activate', 'uses' => 'jlourenco\base\Controllers\AuthController@getActivate'));

    # User account
    Route::group(array('middleware' => 'auth'), function () {
        Route::get('profile', array('as' => 'profile', 'uses' => 'jlourenco\base\Controllers\UsersController@myAccount'));
        Route::post('profile', 'jlourenco\base\Controllers\UsersController@updateAccount');

        Route::get('change-password', array('as' => 'change-password', 'uses' => 'jlourenco\base\Controllers\UsersController@getChangePassword'));
        Route::post('change-password', 'jlourenco\base\Controllers\UsersController@postChangePassword');
    });

});

/**
 * Frontend route group
 *
 * All the "restricted area" routes
 * are defined here.
 */
Route::group(array('prefix' => '/admin', 'middleware' => ['webAdmin', 'auth']), function ()
{

    # Dashboard
    Route::get('/', array('as' => 'home', function()
    {
        return View::make('admin.dashboard');
    }));
    Route::get('/dashboard', array('as' => 'dashbboard', function()
    {
        return View::make('admin.dashboard');
    }));

    # Settings pages
    /* GET  */ Route::get('settings', 'jlourenco\base\Controllers\SettingsController@index');
    /* GET  */ Route::get('settings/{id}/edit', array('as' => 'settings.edit', 'uses' => 'jlourenco\base\Controllers\SettingsController@edit'));
    /* POST */ Route::post('settings/{id}/edit', array('as' => 'settings.update', 'uses' => 'jlourenco\base\Controllers\SettingsController@update'));

    # User Management
    Route::group(array('prefix' => 'users'), function () {
        Route::get('/list', array('as' => 'users', 'uses' => 'jlourenco\base\Controllers\UsersController@getAdminIndex'));
        Route::get('/pending', array('as' => 'users.pending', 'uses' => 'jlourenco\base\Controllers\UsersController@getAdminPending'));
        Route::get('/blocked', array('as' => 'users.blocked', 'uses' => 'jlourenco\base\Controllers\UsersController@getAdminBlocked'));
        Route::get('/accept/{userId}', array('as' => 'users.accept', 'uses' => 'jlourenco\base\Controllers\UsersController@adminAccept'));
        Route::get('/refuse/{userId}', array('as' => 'users.refuse', 'uses' => 'jlourenco\base\Controllers\UsersController@adminRefuse'));
        Route::get('/create', array('as' => 'create.user', 'uses' => 'jlourenco\base\Controllers\UsersController@getAdminCreate'));
        Route::post('/create', 'jlourenco\base\Controllers\UsersController@postAdminCreate');
        Route::get('/list/{userId}/edit', array('as' => 'users.update', 'uses' => 'jlourenco\base\Controllers\UsersController@getAdminEdit'));
        Route::post('/list/{userId}/edit', 'jlourenco\base\Controllers\UsersController@postAdminEdit');
        Route::get('/list/{userId}/delete', array('as' => 'delete/user', 'uses' => 'jlourenco\base\Controllers\UsersController@getAdminDelete'));
        Route::get('/list/{userId}/confirm-delete', array('as' => 'confirm-delete/user', 'uses' => 'jlourenco\base\Controllers\UsersController@getAdminModalDelete'));
        Route::get('/list/{userId}/restore', array('as' => 'restore/user', 'uses' => 'jlourenco\base\Controllers\UsersController@getAdminRestore'));
        Route::get('deleted',array('as' => 'users.deleted', 'uses' => 'jlourenco\base\Controllers\UsersController@getAdminDeletedUsers'));
        Route::get('/list/{userId}', array('as' => 'users.show', 'uses' => 'jlourenco\base\Controllers\UsersController@adminShow'));

        Route::get('/list/{userId}/confirm-remove/{groupId}', array('as' => 'confirm-remove/group', 'uses' => 'jlourenco\base\Controllers\UsersController@getAdminModalRemoveGroup'));
        Route::get('/list/{userId}/remove-group/{groupId}', array('as' => 'remove/group', 'uses' => 'jlourenco\base\Controllers\UsersController@getAdminRemoveGroup'));

        Route::post('/{userId}/add-group', array('as' => 'users.add.group', 'uses' => 'jlourenco\base\Controllers\UsersController@postAdminAddGroup'));
    });

    # Groups Management
    Route::group(array('prefix' => 'groups'), function () {
        Route::get('/list', array('as' => 'groups', 'uses' => 'jlourenco\base\Controllers\GroupsController@index'));
        Route::get('/create', array('as' => 'create.group', 'uses' => 'jlourenco\base\Controllers\GroupsController@getCreate'));
        Route::post('/create', 'jlourenco\base\Controllers\GroupsController@postCreate');
        Route::get('{groupId}/edit', array('as' => 'group.update', 'uses' => 'jlourenco\base\Controllers\GroupsController@getEdit'));
        Route::post('{groupId}/edit', 'jlourenco\base\Controllers\GroupsController@postEdit');
        Route::get('{groupId}/delete', array('as' => 'delete/group', 'uses' => 'jlourenco\base\Controllers\GroupsController@getDelete'));
        Route::get('{groupId}/confirm-delete', array('as' => 'confirm-delete/group', 'uses' => 'jlourenco\base\Controllers\GroupsController@getModalDelete'));
        Route::get('{groupId}', array('as' => 'group.show', 'uses' => 'jlourenco\base\Controllers\GroupsController@show'));
    });

    # Logs pages
    /* GET  */ Route::get('logs', 'jlourenco\base\Controllers\BaseController@getLogs');
    /* GET  */ Route::get('getLogs', 'jlourenco\base\Controllers\BaseController@ajaxGetLogs');

    # Queue pages
    /* GET  */ Route::get('queues', 'jlourenco\base\Controllers\BaseController@getQueues');
    /* GET  */ Route::get('getQueues', 'jlourenco\base\Controllers\BaseController@ajaxGetQueues');

    # Visits pages
    /* GET  */ Route::get('visits', 'jlourenco\base\Controllers\BaseController@getVisits');
    /* GET  */ Route::get('getVisits', 'jlourenco\base\Controllers\BaseController@ajaxGetVisits');

});
