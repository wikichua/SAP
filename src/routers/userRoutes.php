<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web', 'auth', 'can:Access Admin Panel']], function () {
    Route::group(['prefix' => 'user', 'namespace' => config('sap.controller_namespace') . '\Admin'], function () {
        Route::match(['get', 'head'], 'list', 'UserController@index')->name('user.list');
        Route::match(['get', 'head'], 'create', 'UserController@create')->name('user.create');
        Route::match(['get', 'head'], '{user}/edit', 'UserController@edit')->name('user.edit');
        Route::match(['get', 'head'], '{user}/read', 'UserController@show')->name('user.show');
        Route::match(['post'], 'store', 'UserController@store')->name('user.store');
        Route::match(['put', 'patch'], '{user}/update', 'UserController@update')->name('user.update');
        Route::match(['delete'], '{user}/delete', 'UserController@destroy')->name('user.destroy');

        Route::match(['get', 'head'], '{user}/editPassword', 'UserController@editPassword')->name('user.editPassword');
        Route::match(['put', 'patch'], '{user}/updatePassword', 'UserController@updatePassword')->name('user.updatePassword');
    });
});
