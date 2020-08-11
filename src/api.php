<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'api'], function () {
    Route::match(['post'], '/login', config('sap.controller_namespace').'\Api\AuthController@login')->name('api.login');
    Route::group(['middleware' => ['auth:sanctum'], 'namespace' => config('sap.controller_namespace')], function () {
        Route::match(['get', 'head'], 'profile', 'Api\ProfileController@index')->name('api.profile');
    });
});
