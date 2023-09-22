<?php

use Dino\Base\Helpers\BaseHelper;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Dino\Base\Http\Controllers', 'middleware' => 'web'], function () {
    Route::group(['prefix' => BaseHelper::getAdminPrefix()], function () { // ! add 'middleware' => 'auth'
    });
});
