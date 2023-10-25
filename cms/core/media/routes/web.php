<?php

use Dino\Base\Helpers\BaseHelper;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Dino\Media\Http\Controllers', 'middleware' => 'web'], function () {
    Route::group(['prefix' => BaseHelper::getAdminPrefix() . '/media', 'middleware' => 'auth'], function () {

        Route::get('/', [
            'as' => 'media.admin.index',
            'uses' => 'MediaController@index',
            'permissions' => [], // ? Permissions to access this route
        ]);
    });
});
