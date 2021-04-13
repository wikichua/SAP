<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => config('sap.custom_admin_path'), 'middleware' => ['web', 'auth_admin', 'can:Access Admin Panel']], function () {
    Route::group(['prefix' => 'carousel', 'namespace' => config('sap.controller_namespace').'\Admin'], function () {
        Route::match(['get', 'head'], 'list', 'CarouselController@index')->name('carousel.list');

        Route::match(['get', 'head'], '{model}/read', 'CarouselController@show')->name('carousel.show');

        Route::match(['get', 'head'], 'create', 'CarouselController@create')->name('carousel.create');
        Route::match(['post'], 'store', 'CarouselController@store')->name('carousel.store');

        Route::match(['get', 'head'], '{model}/edit', 'CarouselController@edit')->name('carousel.edit');
        Route::match(['put', 'patch'], '{model}/update', 'CarouselController@update')->name('carousel.update');

        Route::match(['delete'], '{model}/delete', 'CarouselController@destroy')->name('carousel.destroy');

        Route::match(['get', 'head'], 'orderable/{groupValue?}/{brand_id?}', 'CarouselController@orderable')->name('carousel.orderable');
        Route::match(['post'], 'orderable/update/{groupValue?}/{brand_id?}', 'CarouselController@orderableUpdate')->name('carousel.orderableUpdate');
    });
});
