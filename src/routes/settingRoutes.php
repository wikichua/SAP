<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => config('sap.custom_admin_path'), 'middleware' => ['web', 'auth_admin', 'can:Access Admin Panel']], function () {
    Route::group(['prefix' => 'setting', 'namespace' => config('sap.controller_namespace').'\Admin'], function () {
        Route::match(['get', 'head'], 'list', 'SettingController@index')->name('setting.list');

        Route::match(['get', 'head'], '{setting}/read', 'SettingController@show')->name('setting.show');

        Route::match(['get', 'head'], 'create', 'SettingController@create')->name('setting.create');
        Route::match(['post'], 'store', 'SettingController@store')->name('setting.store');

        Route::match(['get', 'head'], '{setting}/edit', 'SettingController@edit')->name('setting.edit');
        Route::match(['put', 'patch'], '{setting}/update', 'SettingController@update')->name('setting.update');

        Route::match(['delete'], '{setting}/delete', 'SettingController@destroy')->name('setting.destroy');
    });
});
