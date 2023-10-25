<?php

use Dino\Base\Helpers\BaseHelper;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Dino\Setting\Http\Controllers', 'middleware' => 'web'], function () {
    Route::group(['prefix' => BaseHelper::getAdminPrefix() . '/settings', 'middleware' => 'auth'], function () {

        Route::get('/', [
            'as' => 'setting.admin.index',
            'uses' => 'SettingController@index',
            'permissions' => [], // ? Permissions to access this route
        ]);
        Route::post('/', [
            'as' => 'setting.admin.store',
            'uses' => 'SettingController@store',
            'permissions' => [], // ? Permissions to access this route
        ]);
    });
});
