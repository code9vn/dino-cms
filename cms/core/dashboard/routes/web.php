<?php

use Dino\Base\Helpers\BaseHelper;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Dino\Dashboard\Http\Controllers', 'middleware' => 'web'], function () {
    Route::group(['prefix' => BaseHelper::getAdminPrefix()], function () { // ! add 'middleware' => 'auth'

        Route::get('/', [
            'as' => 'dashboard.index',
            'uses' => 'DashboardController@index',
            'permissions' => [], // ? Permissions to access this route
        ]);
    });
});
