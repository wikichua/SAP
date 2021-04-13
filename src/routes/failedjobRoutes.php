<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => config('sap.custom_admin_path'), 'middleware' => ['web', 'auth_admin', 'can:Access Admin Panel']], function () {
    Route::group(['prefix' => 'failed_job', 'namespace' => config('sap.controller_namespace').'\Admin'], function () {
        Route::match(['get', 'head'], 'list', 'FailedJobController@index')->name('failed_job.list');
        Route::match(['get', 'head'], '{failed_job}/read', 'FailedJobController@show')->name('failed_job.show');
        Route::match(['post'], '{failed_job}/retry', 'FailedJobController@retry')->name('failed_job.retry');
    });
});
