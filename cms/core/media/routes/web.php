<?php

use Dino\Base\Helpers\BaseHelper;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Dino\Media\Http\Controllers', 'middleware' => 'web'], function () {
    Route::group(['prefix' => BaseHelper::getAdminPrefix() . '/media'], function () { // ! add 'middleware' => 'auth'

        Route::get('/', [
            'as' => 'media.index',
            'uses' => 'MediaController@index',
            'permissions' => [], // ? Permissions to access this route
        ]);
    });
});
