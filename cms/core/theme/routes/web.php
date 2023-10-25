<?php

use Dino\Base\Helpers\BaseHelper;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Dino\Theme\Http\Controllers', 'middleware' => 'web'], function () {
    Route::group(['prefix' => BaseHelper::getAdminPrefix() . '/theme', 'middleware' => 'auth'], function () {

        Route::get('/', [
            'as' => 'theme.admin.index',
            'uses' => 'ThemeController@index',
            'permissions' => [], // ? Permissions to access this route
        ]);

        Route::put('update', [
            'as' => 'theme.admin.update',
            'uses' => 'ThemeController@update',
            'permissions' => [], // ? Permissions to access this route
        ]);

        Route::delete('remove', [
            'as' => 'theme.admin.remove',
            'uses' => 'ThemeController@destroy',
            'permissions' => [], // ? Permissions to access this route
        ]);
    });
});
