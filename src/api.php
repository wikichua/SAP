<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'api'], function () {
    Route::match(['post'], '/login', config('sap.controller_namespace').'\Api\AuthController@login')->name('api.login');
});
