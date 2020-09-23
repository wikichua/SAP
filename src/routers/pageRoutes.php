<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => config('sap.custom_admin_path'),'middleware' => ['web', 'auth_admin', 'can:Access Admin Panel']], function () {
    Route::group(['prefix' => 'page', 'namespace' => config('sap.controller_namespace') . '\Admin'], function () {
        Route::match(['get', 'head'], 'list', 'PageController@index')->name('page.list');

        Route::match(['get', 'head'], '{page}/read', 'PageController@show')->name('page.show');
        Route::match(['get', 'head'], '{page}/preview', 'PageController@preview')->name('page.preview');

        Route::match(['get', 'head'], 'create', 'PageController@create')->name('page.create');
        Route::match(['post'], 'store', 'PageController@store')->name('page.store');

        Route::match(['get', 'head'], '{page}/edit', 'PageController@edit')->name('page.edit');
        Route::match(['put', 'patch'], '{page}/update', 'PageController@update')->name('page.update');

        Route::match(['delete'], '{page}/delete', 'PageController@destroy')->name('page.destroy');

        Route::match(['get', 'head'], '{brand_id}/templates', 'PageController@templates')->name('page.templates');
    });
});
