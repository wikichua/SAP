<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => config('sap.custom_admin_path'),'middleware' => ['web', 'auth_admin', 'can:Access Admin Panel']], function () {
    Route::group(['prefix' => 'report', 'namespace' => config('sap.controller_namespace') . '\Admin'], function () {
        Route::match(['get', 'head'], 'list', 'ReportController@index')->name('report.list');

        Route::match(['get', 'head'], '{report}/read', 'ReportController@show')->name('report.show');
        Route::match(['get', 'head'], '{report}/preview', 'ReportController@preview')->name('report.preview');

        Route::match(['get', 'head'], 'create', 'ReportController@create')->name('report.create');
        Route::match(['post'], 'store', 'ReportController@store')->name('report.store');

        Route::match(['get', 'head'], '{report}/edit', 'ReportController@edit')->name('report.edit');
        Route::match(['put', 'patch'], '{report}/update', 'ReportController@update')->name('report.update');

        Route::match(['delete'], '{report}/delete', 'ReportController@destroy')->name('report.destroy');
    });
});
