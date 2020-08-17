<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::group(['middleware' => ['web'], 'namespace' => config('sap.controller_namespace').'\Pub'], function () {
    Route::group(['middleware' => ['guest']], function () {
        Route::match(['get', 'head'], '', 'PubController@index')->name('pub.index');
    });
    Route::group(['prefix' => config('sap.custom_pub_path')], function () {
        Route::group(['middleware' => ['guest']], function () {
            Route::match(['get', 'head'], 'login', 'LoginController@showLoginForm')->name('pub.login');
            Route::match(['post'], 'login', 'LoginController@login');
        });
        Route::group(['middleware' => ['auth']], function () {
            Route::match(['get', 'head'], 'home', 'PubController@home')->name('pub.home');
            Route::get('logout', 'LoginController@logout')->name('pub.logout');
            Route::match(['get', 'head'], '/', 'PubController@chatify')->name('pub.chatify');
        });
    });
});
