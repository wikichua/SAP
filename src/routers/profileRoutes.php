<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web', 'auth', 'can:Access Admin Panel']], function () {
    Route::group(['prefix' => 'profile'], function () {
        Route::view('', 'sap::layouts.panel');
        Route::view('password', 'sap::layouts.panel');
    });
    Route::group(['prefix' => 'api/profile', 'namespace' => config('vam.controller_namespace') . '\Admin'], function () {
        Route::match(['get', 'head'], 'read', 'ProfileController@show')->name('profile.show');
        Route::match(['put', 'patch'], 'update', 'ProfileController@update')->name('profile.update');
        Route::match(['put', 'patch'], 'updatePassword', 'ProfileController@updatePassword')->name('profile.updatePassword');
    });
});
