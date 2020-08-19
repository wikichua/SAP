<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => config('sap.custom_admin_path'),'middleware' => ['web', 'auth_admin', 'can:Access Admin Panel']], function () {
    Route::group(['prefix' => 'component', 'namespace' => config('sap.controller_namespace'). '\Admin'], function () {
        Route::match(['get', 'head'], 'list', 'ComponentController@index')->name('component.list');

        Route::match(['get', 'head'], '{model}/read', 'ComponentController@show')->name('component.show');
        Route::match(['post'], '{model}/try', 'ComponentController@try')->name('component.try');
    });
});
