<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web', 'auth', 'can:Access Admin Panel']], function () {
    Route::group(['prefix' => 'permission'], function () {
        Route::view('', 'sap::layouts.panel')->middleware('can:Read Permissions');
        Route::view('{permission}/show', 'sap::layouts.panel')->middleware('can:Read Permissions');
        Route::view('create', 'sap::layouts.panel')->middleware('can:Create Permissions');
        Route::view('{permission}/edit', 'sap::layouts.panel')->middleware('can:Update Permissions');
    });
    Route::group(['prefix' => 'api/permission', 'namespace' => config('vam.controller_namespace') . '\Admin'], function () {
        Route::match(['get', 'head'], 'list', 'PermissionController@index')->name('permission.index');
        Route::match(['post'], 'store', 'PermissionController@store')->name('permission.store');
        Route::match(['get', 'head'], '{permission}/read', 'PermissionController@show')->name('permission.show');
        Route::match(['put', 'patch'], '{permission}/update', 'PermissionController@update')->name('permission.update');
        Route::match(['delete'], '{permission}/delete', 'PermissionController@destroy')->name('permission.destroy');

        Route::match(['get', 'head'], 'checkboxes', 'PermissionController@checkboxes')->name('permission.checkboxes');
    });
});
