<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => config('sap.custom_admin_path'),'middleware' => ['web', 'auth_admin', 'can:Access Admin Panel']], function () {
    Route::group(['prefix' => 'mailer', 'namespace' => config('sap.controller_namespace') . '\Admin'], function () {
        Route::match(['get', 'head'], 'list', 'MailerController@index')->name('mailer.list');
        Route::match(['get', 'head'], '{mailer}/read', 'MailerController@show')->name('mailer.show');

        Route::match(['get', 'head'], '{mailer}/edit', 'MailerController@edit')->name('mailer.edit');
        Route::match(['put', 'patch'], '{mailer}/update', 'MailerController@update')->name('mailer.update');

        Route::match(['delete'], '{mailer}/delete', 'MailerController@destroy')->name('mailer.destroy');
    });
});
