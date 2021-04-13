<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => config('sap.custom_admin_path'), 'middleware' => ['web', 'auth_admin', 'can:Access Admin Panel']], function () {
    Route::group(['prefix' => 'cronjob', 'namespace' => config('sap.controller_namespace').'\Admin'], function () {
        Route::match(['get', 'head'], 'list', 'CronjobController@index')->name('cronjob.list');
        Route::match(['get', 'head'], '{cronjob}/read', 'CronjobController@show')->name('cronjob.show');

        Route::match(['get', 'head'], 'create', 'CronjobController@create')->name('cronjob.create');
        Route::match(['post'], 'store', 'CronjobController@store')->name('cronjob.store');

        Route::match(['get', 'head'], '{cronjob}/edit', 'CronjobController@edit')->name('cronjob.edit');
        Route::match(['put', 'patch'], '{cronjob}/update', 'CronjobController@update')->name('cronjob.update');

        Route::match(['delete'], '{cronjob}/delete', 'CronjobController@destroy')->name('cronjob.destroy');
    });
});
