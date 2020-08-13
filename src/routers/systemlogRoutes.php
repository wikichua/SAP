<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web', 'auth', 'can:Access Admin Panel']], function () {
    Route::group(['prefix' => 'system_log', 'namespace' => config('sap.controller_namespace') . '\Admin'], function () {
        Route::match(['get', 'head'], '', 'LogViewerController@index')->name('system_log.list');
    });
});
