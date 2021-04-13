<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => config('sap.custom_admin_path'), 'middleware' => ['web', 'auth_admin', 'can:Access Admin Panel']], function () {
    Route::group(['prefix' => 'versionizer', 'namespace' => config('sap.controller_namespace').'\Admin'], function () {
        Route::match(['get', 'head'], 'list', 'VersionizerController@index')->name('versionizer.list');
        Route::match(['get', 'head'], '{versionizer}/read', 'VersionizerController@show')->name('versionizer.show');
        Route::match(['put', 'patch'], '{versionizer}/revert', 'VersionizerController@revert')->name('versionizer.revert');
        Route::match(['delete'], '{versionizer}/delete', 'VersionizerController@destroy')->name('versionizer.destroy');
    });
});
