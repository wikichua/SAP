<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web', 'auth', 'can:Access Admin Panel']], function () {
    Route::group(['prefix' => 'activitylog'], function () {
        Route::view('', 'sap::layouts.panel')->middleware('can:Read Activity Logs');
        Route::view('{activitylog}/show', 'sap::layouts.panel')->middleware('can:Read Activity Logs');
    });
    Route::group(['prefix' => 'api/activitylog', 'namespace' => config('vam.controller_namespace') . '\Admin'], function () {
        Route::match(['get', 'head'], 'list', 'ActivityLogController@index')->name('activitylog.index');
        Route::match(['get', 'head'], '{activitylog}/read', 'ActivityLogController@show')->name('activitylog.show');
    });
});
