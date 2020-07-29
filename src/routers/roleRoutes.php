<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web', 'auth', 'can:Access Admin Panel']], function () {
    Route::group(['prefix' => 'role'], function () {
        Route::view('', 'sap::layouts.panel')->middleware('can:Read Roles');
        Route::view('{role}/show', 'sap::layouts.panel')->middleware('can:Read Roles');
        Route::view('create', 'sap::layouts.panel')->middleware('can:Create Roles');
        Route::view('{role}/edit', 'sap::layouts.panel')->middleware('can:Update Roles');
    });
    Route::group(['prefix' => 'api/role', 'namespace' => config('vam.controller_namespace') . '\Admin'], function () {
        Route::match(['get', 'head'], 'list', 'RoleController@index')->name('role.index');
        Route::match(['post'], 'store', 'RoleController@store')->name('role.store');
        Route::match(['get', 'head'], '{role}/read', 'RoleController@show')->name('role.show');
        Route::match(['put', 'patch'], '{role}/update', 'RoleController@update')->name('role.update');
        Route::match(['delete'], '{role}/delete', 'RoleController@destroy')->name('role.destroy');

        Route::match(['get', 'head'], 'checkboxes', 'RoleController@checkboxes')->name('role.checkboxes');
    });
});
