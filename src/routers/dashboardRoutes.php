<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => config('sap.custom_admin_path'),'middleware' => ['web', 'auth_admin', 'can:Access Admin Panel']], function () {
    Route::group(['prefix' => 'laravel-filemanager'], function () {
        \UniSharp\LaravelFilemanager\Lfm::routes();
    });

    Route::group(['prefix' => '', 'namespace' => config('sap.controller_namespace') . '\Admin'], function () {
        Route::match(['get', 'head'], '/', 'DashboardController@index')->name('admin');
        Route::match(['get', 'head'], '/chat', 'DashboardController@chatify')->name('chatify.home');
        Route::match(['get', 'head'], '/lfm', 'DashboardController@lfm')->name('lfm.home');
    });
});
