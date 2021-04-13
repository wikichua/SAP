<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => config('sap.custom_admin_path'), 'middleware' => ['web', 'auth_admin', 'can:Access Admin Panel']], function () {
    Route::group(['prefix' => 'role', 'namespace' => config('sap.controller_namespace').'\Admin'], function () {
        Route::match(['get', 'head'], 'list', 'RoleController@index')->name('role.list');

        Route::match(['get', 'head'], '{role}/read', 'RoleController@show')->name('role.show');

        Route::match(['get', 'head'], 'create', 'RoleController@create')->name('role.create');
        Route::match(['post'], 'store', 'RoleController@store')->name('role.store');

        Route::match(['get', 'head'], '{role}/edit', 'RoleController@edit')->name('role.edit');
        Route::match(['put', 'patch'], '{role}/update', 'RoleController@update')->name('role.update');

        Route::match(['delete'], '{role}/delete', 'RoleController@destroy')->name('role.destroy');
    });
});
