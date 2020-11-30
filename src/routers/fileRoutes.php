<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => config('sap.custom_admin_path'),'middleware' => ['web', 'auth_admin', 'can:Access Admin Panel']], function () {
    Route::group(['prefix' => 'file', 'namespace' => config('sap.controller_namespace') . '\Admin'], function () {
        Route::match(['post'], 'directories', 'FileController@directories')->name('folder.directories');
        Route::match(['put'], 'directories/make/{path?}', 'FileController@make')->name('folder.make');
        Route::match(['put'], 'directories/{path?}/rename', 'FileController@change')->name('folder.change');
        Route::match(['put'], 'directories/{path?}/clone', 'FileController@clone')->name('folder.clone');
        Route::match(['delete'], 'directories/{path?}/remove', 'FileController@remove')->name('folder.remove');

        // Route::match(['get', 'head'], '{file}/read', 'FileController@show')->name('file.show');

        Route::match(['get', 'head'], 'list/{path?}', 'FileController@index')->name('file.list');
        Route::match(['post'], 'upload/{path?}', 'FileController@upload')->name('file.upload');
        Route::match(['put', 'patch'], '{path?}/rename', 'FileController@rename')->name('file.rename');
        Route::match(['put', 'patch'], '{path?}/duplicate', 'FileController@duplicate')->name('file.duplicate');
        Route::match(['delete'], '{path?}/delete', 'FileController@destroy')->name('file.destroy');
    });
});
