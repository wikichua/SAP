<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web'], 'namespace' => config('sap.controller_namespace').'\Pub'], function () {
    Route::group(['middleware' => ['guest']], function () {
        if (config('sap.custom_pub_path') != '' && config('sap.custom_admin_path') != '') {
            Route::redirect('/home', '/')->name('home');
            Route::match(['get', 'head'], '', 'PubController@index')->name('pub.index');
        }
    });
    Route::group(['prefix' => config('sap.custom_pub_path')], function () {
        Route::group(['middleware' => ['guest']], function () {
            Route::match(['get', 'head'], 'login', 'LoginController@showLoginForm')->name('pub.login');
            Route::match(['post'], 'login', 'LoginController@login');

            Route::match(['get','post'], 'login/{provider}', 'LoginController@redirectToProvider')->name('pub.social.login');
            Route::match(['get','post'], 'login/{provider}/callback', 'LoginController@handleProviderCallback')->name('pub.social.callback');
        });
        Route::group(['middleware' => ['auth']], function () {
            Route::match(['get', 'head'], '/', 'PubController@home')->name('pub.home');
            Route::get('logout', 'LoginController@logout')->name('pub.logout');
        });
    });
});
