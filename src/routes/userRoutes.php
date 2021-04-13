<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => config('sap.custom_admin_path'), 'middleware' => ['web', 'auth_admin', 'can:Access Admin Panel']], function () {
    Route::group(['prefix' => 'user', 'namespace' => config('sap.controller_namespace').'\Admin'], function () {
        Route::match(['get', 'head'], 'list', 'UserController@index')->name('user.list');

        Route::match(['get', 'head'], '{user}/read', 'UserController@show')->name('user.show');

        Route::match(['get', 'head'], 'create', 'UserController@create')->name('user.create');
        Route::match(['post'], 'store', 'UserController@store')->name('user.store');

        Route::match(['get', 'head'], '{user}/edit', 'UserController@edit')->name('user.edit');
        Route::match(['put', 'patch'], '{user}/update', 'UserController@update')->name('user.update');

        Route::match(['delete'], '{user}/delete', 'UserController@destroy')->name('user.destroy');

        Route::match(['get', 'head'], '{user}/editPassword', 'UserController@editPassword')->name('user.editPassword');
        Route::match(['put', 'patch'], '{user}/updatePassword', 'UserController@updatePassword')->name('user.updatePassword');

        // pat => personal access token
        Route::group(['prefix' => '{user}/pat', 'namespace' => config('sap.controller_namespace').'\Admin'], function () {
            Route::match(['get', 'head'], 'list', 'UserPersonalAccessTokenController@index')->name('pat.list');
            Route::match(['get', 'head'], 'create', 'UserPersonalAccessTokenController@create')->name('pat.create');
            Route::match(['post'], 'store', 'UserPersonalAccessTokenController@store')->name('pat.store');
            Route::match(['delete'], '{pat}/delete', 'UserPersonalAccessTokenController@destroy')->name('pat.destroy');
        });
    });
});
