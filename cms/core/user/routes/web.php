<?php

use Dino\Base\Helpers\BaseHelper;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Dino\User\Http\Controllers', 'middleware' => 'web'], function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::get('login', [
            'as' => 'login',
            'uses' => 'AuthController@login',
            'permissions' => [], // ? Permissions to access this route
        ]);

        Route::post('login', [
            'as' => 'login.post',
            'uses' => 'AuthController@doLogin',
            'permissions' => [], // ? Permissions to access this route
        ]);

        Route::get('logout', [
            'as'         => 'logout',
            'uses'       => 'AuthController@logout',
            'middleware' => 'auth'
        ]);
    });

    Route::group(['prefix' => BaseHelper::getAdminPrefix(), 'middleware' => 'auth'], function () {
        Route::group(['prefix' => 'users'], function () {
            Route::get('/', [
                'as' => 'user.admin.index',
                'uses' => 'UserController@index',
                'permissions' => [], // ? Permissions to access this route
            ]);
            Route::get('create', [
                'as' => 'user.admin.create',
                'uses' => 'UserController@create',
                'permissions' => [], // ? Permissions to access this route
            ]);
            Route::post('create', [
                'as' => 'user.admin.store',
                'uses' => 'UserController@store',
                'permissions' => [], // ? Permissions to access this route
            ]);
            Route::get('edit/{id}', [
                'as' => 'user.admin.edit',
                'uses' => 'UserController@edit',
                'permissions' => [], // ? Permissions to access this route
            ]);
            Route::post('edit/{id}', [
                'as' => 'user.admin.update',
                'uses' => 'UserController@update',
                'permissions' => [], // ? Permissions to access this route
            ]);
            Route::delete('delete/{id}', [
                'as' => 'user.admin.delete',
                'uses' => 'UserController@delete',
                'permissions' => [], // ? Permissions to access this route
            ]);
        });

        Route::group(['prefix' => 'roles'], function () {
            Route::get('/', [
                'as' => 'role.admin.index',
                'uses' => 'RoleController@index',
                'permissions' => [], // ? Permissions to access this route
            ]);
            Route::get('create', [
                'as' => 'role.admin.create',
                'uses' => 'RoleController@create',
                'permissions' => [], // ? Permissions to access this route
            ]);
            Route::post('create', [
                'as' => 'role.admin.store',
                'uses' => 'RoleController@store',
                'permissions' => [], // ? Permissions to access this route
            ]);
            Route::get('edit/{id}', [
                'as' => 'role.admin.edit',
                'uses' => 'RoleController@edit',
                'permissions' => [], // ? Permissions to access this route
            ]);
            Route::post('edit/{id}', [
                'as' => 'role.admin.update',
                'uses' => 'RoleController@update',
                'permissions' => [], // ? Permissions to access this route
            ]);
            Route::delete('delete/{id}', [
                'as' => 'role.admin.delete',
                'uses' => 'RoleController@delete',
                'permissions' => [], // ? Permissions to access this route
            ]);
        });
    });
});
