<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => config('sap.custom_admin_path'),'middleware' => ['web', 'auth_admin', 'can:Access Admin Panel']], function () {
    if (class_exists('\UniSharp\LaravelFilemanager\Lfm')) {
        Route::group(['prefix' => 'laravel-filemanager'], function () {
            \UniSharp\LaravelFilemanager\Lfm::routes();
        });
    }

    Route::group(['prefix' => '', 'namespace' => config('sap.controller_namespace') . '\Admin'], function () {
        Route::match(['get', 'head'], '/', 'DashboardController@index')->name('admin');
        Route::match(['get', 'head'], '/lfm', 'DashboardController@lfm')->name('lfm.home');
        Route::match(['get', 'head'], '/seo', 'DashboardController@seo')->name('seo.home');
        Route::match(['get', 'head'], '/wiki/{file?}', 'DashboardController@wiki')->name('wiki.home');
    });
});
