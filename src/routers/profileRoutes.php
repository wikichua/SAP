<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web', 'auth', 'can:Access Admin Panel']], function () {
    Route::group(['prefix' => 'profile', 'namespace' => config('sap.controller_namespace') . '\Admin'], function () {
        Route::match(['get', 'head'], 'show', 'ProfileController@show')->name('profile.show');
        
        Route::match(['get', 'head'], 'edit', 'ProfileController@edit')->name('profile.edit');
        Route::match(['put', 'patch'], 'update', 'ProfileController@update')->name('profile.update');

        Route::match(['get', 'head'], 'editPassword', 'ProfileController@editPassword')->name('profile.editPassword');
        Route::match(['put', 'patch'], 'updatePassword', 'ProfileController@updatePassword')->name('profile.updatePassword');
    });
});
