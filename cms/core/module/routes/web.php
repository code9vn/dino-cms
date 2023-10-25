<?php

use Dino\Base\Helpers\BaseHelper;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Dino\Module\Http\Controllers', 'middleware' => 'web'], function () {
    Route::group(['prefix' => BaseHelper::getAdminPrefix() . '/module', 'middleware' => 'auth'], function () {

        Route::get('/', [
            'as' => 'module.admin.index',
            'uses' => 'ModuleController@index',
            'permissions' => [], // ? Permissions to access this route
        ]);

        Route::put('update', [
            'as' => 'module.admin.update',
            'uses' => 'ModuleController@update',
            'permissions' => [], // ? Permissions to access this route
        ]);

        Route::delete('remove', [
            'as' => 'module.admin.remove',
            'uses' => 'ModuleController@destroy',
            'permissions' => [], // ? Permissions to access this route
        ]);
    });
});
