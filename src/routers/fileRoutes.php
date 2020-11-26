<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => config('sap.custom_admin_path'),'middleware' => ['web', 'auth_admin', 'can:Access Admin Panel']], function () {
    Route::group(['prefix' => 'file', 'namespace' => config('sap.controller_namespace') . '\Admin'], function () {
        Route::match(['post'], 'directories', 'FileController@directories')->name('file.directories');
        Route::match(['get', 'head'], 'list/{path?}', 'FileController@index')->name('file.list');

        // Route::match(['get', 'head'], '{file}/read', 'FileController@show')->name('file.show');

        Route::match(['get', 'head'], '{path?}/upload', 'FileController@upload')->name('file.upload');
        Route::match(['post'], '{path?}/store', 'FileController@store')->name('file.store');

        Route::match(['get', 'head'], '{path?}/rename', 'FileController@rename')->name('file.rename');
        Route::match(['put', 'patch'], '{path?}/update', 'FileController@update')->name('file.update');

        Route::match(['get', 'head'], '{path?}/duplicate', 'FileController@duplicate')->name('file.duplicate');
        Route::match(['put', 'patch'], '{path?}/copied', 'FileController@copied')->name('file.copied');

        Route::match(['delete'], '{path}/delete', 'FileController@destroy')->name('file.destroy');
    });
});
