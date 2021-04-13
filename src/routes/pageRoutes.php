<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => config('sap.custom_admin_path'), 'middleware' => ['web', 'auth_admin', 'can:Access Admin Panel']], function () {
    Route::group(['prefix' => 'page', 'namespace' => config('sap.controller_namespace').'\Admin'], function () {
        Route::match(['get'], 'list', 'PageController@index')->name('page.list');

        Route::match(['get'], '{page}/read', 'PageController@show')->name('page.show');
        Route::match(['get'], '{page}/preview', 'PageController@preview')->name('page.preview');

        Route::match(['get'], 'create', 'PageController@create')->name('page.create');
        Route::match(['post'], 'store', 'PageController@store')->name('page.store');

        Route::match(['get'], '{page}/edit', 'PageController@edit')->name('page.edit');
        Route::match(['put', 'patch'], '{page}/update', 'PageController@update')->name('page.update');

        Route::match(['delete'], '{page}/delete', 'PageController@destroy')->name('page.destroy');

        Route::match(['get'], '{brand_id}/templates', 'PageController@templates')->name('page.templates');

        Route::match(['post'], '{page}/replicate', 'PageController@replicate')->name('page.replicate');

        Route::match(['get'], '{page}/migration', 'PageController@migration')->name('page.migration');
    });
});
