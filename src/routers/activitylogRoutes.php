<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web', 'auth', 'can:Access Admin Panel']], function () {
    Route::group(['prefix' => 'activity_log', 'namespace' => config('sap.controller_namespace') . '\Admin'], function () {
        Route::match(['get', 'head'], 'list', 'ActivityLogController@index')->name('activity_log.list');

        Route::match(['get', 'head'], '{activity_log}/read', 'ActivityLogController@show')->name('activity_log.show');
    });
});
