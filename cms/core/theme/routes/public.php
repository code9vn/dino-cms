<?php

use Dino\Theme\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

Route::group(['controller' => PublicController::class, 'middleware' => ['web']], function () {
    Route::group(apply_filters(BASE_FILTER_GROUP_PUBLIC_ROUTE, []), function () {
        // event(new ThemeRoutingBeforeEvent(app()->make('router')));

        Route::get('/', [
            'as' => 'public.index',
            'uses' => 'getIndex',
        ]);

        Route::get('{slug?}', [
            'as' => 'public.single',
            'uses' => 'getView',
        ]);

        // event(new ThemeRoutingAfterEvent(app()->make('router')));
    });
});
