<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => config('sap.custom_admin_path'),'middleware' => ['web', 'auth_admin', 'can:Access Admin Panel']], function () {
    Route::group(['prefix' => 'pusher', 'namespace' => config('sap.controller_namespace') . '\Admin'], function () {
        Route::match(['get', 'head'], 'list', 'PusherController@index')->name('pusher.list');

        Route::match(['get', 'head'], '{pusher}/read', 'PusherController@show')->name('pusher.show');

        Route::match(['get', 'head'], 'create', 'PusherController@create')->name('pusher.create');
        Route::match(['post'], 'store', 'PusherController@store')->name('pusher.store');

        Route::match(['get', 'head'], '{pusher}/edit', 'PusherController@edit')->name('pusher.edit');
        Route::match(['put', 'patch'], '{pusher}/update', 'PusherController@update')->name('pusher.update');

        Route::match(['delete'], '{pusher}/delete', 'PusherController@destroy')->name('pusher.destroy');

        Route::match(['post'], 'push/{pusher}', 'PusherController@push')->name('pusher.push');
    });
});
