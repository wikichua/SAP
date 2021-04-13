<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'api'], function () {
    Route::group(['prefix' => 'profile', 'middleware' => ['auth:sanctum'], 'namespace' => config('sap.controller_namespace').'\Api'], function () {
        Route::match(['get', 'head'], 'read', 'ProfileController@show')->name('api.profile');
        Route::match(['put', 'patch'], 'update', 'ProfileController@update')->name('api.profile.update');
        Route::match(['put', 'patch'], 'updatePassword', 'ProfileController@updatePassword')->name('api.profile.updatePassword');
    });
});
