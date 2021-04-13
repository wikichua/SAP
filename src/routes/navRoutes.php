<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => config('sap.custom_admin_path'), 'middleware' => ['web', 'auth_admin', 'can:Access Admin Panel']], function () {
    Route::group(['prefix' => 'nav', 'namespace' => config('sap.controller_namespace').'\Admin'], function () {
        Route::match(['get'], 'list', 'NavController@index')->name('nav.list');

        Route::match(['get'], '{nav}/read', 'NavController@show')->name('nav.show');

        Route::match(['get'], 'create', 'NavController@create')->name('nav.create');
        Route::match(['post'], 'store', 'NavController@store')->name('nav.store');

        Route::match(['get'], '{nav}/edit', 'NavController@edit')->name('nav.edit');
        Route::match(['put', 'patch'], '{nav}/update', 'NavController@update')->name('nav.update');

        Route::match(['delete'], '{nav}/delete', 'NavController@destroy')->name('nav.destroy');

        Route::match(['get'], '{brand_id}/pages', 'NavController@pages')->name('nav.pages');

        Route::match(['post'], '{nav}/replicate', 'NavController@replicate')->name('nav.replicate');

        Route::match(['get'], 'orderable/{groupValue?}/{brand_id?}', 'NavController@orderable')->name('nav.orderable');
        Route::match(['post'], 'orderable/update/{groupValue?}/{brand_id?}', 'NavController@orderableUpdate')->name('nav.orderableUpdate');

        Route::match(['get'], '{nav}/migration', 'NavController@migration')->name('nav.migration');
    });
});
