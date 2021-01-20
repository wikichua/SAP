<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => config('sap.custom_admin_path'),'middleware' => ['web', 'auth_admin', 'can:Access Admin Panel']], function () {
    Route::group(['prefix' => 'brand', 'namespace' => config('sap.controller_namespace'). '\Admin'], function () {
        Route::match(['get', 'head'], 'list', 'BrandController@index')->name('brand.list');

        Route::match(['get', 'head'], '{model}/read', 'BrandController@show')->name('brand.show');

        Route::match(['get', 'head'], '{model}/edit', 'BrandController@edit')->name('brand.edit');
        Route::match(['put', 'patch'], '{model}/update', 'BrandController@update')->name('brand.update');
    });
});
