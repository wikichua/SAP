<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => config('sap.custom_admin_path'),'middleware' => ['web', 'auth_admin', 'can:Access Admin Panel']], function () {
    Route::group(['prefix' => 'permission', 'namespace' => config('sap.controller_namespace') . '\Admin'], function () {
        Route::match(['get', 'head'], 'list', 'PermissionController@index')->name('permission.list');

        Route::match(['get', 'head'], '{permission}/read', 'PermissionController@show')->name('permission.show');

        Route::match(['get', 'head'], 'create', 'PermissionController@create')->name('permission.create');
        Route::match(['post'], 'store', 'PermissionController@store')->name('permission.store');

        Route::match(['get', 'head'], '{permission}/edit', 'PermissionController@edit')->name('permission.edit');
        Route::match(['put', 'patch'], '{permission}/update', 'PermissionController@update')->name('permission.update');

        Route::match(['delete'], '{permission}/delete', 'PermissionController@destroy')->name('permission.destroy');
    });
});
