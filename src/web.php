<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

Route::redirect('', '/dashboard');
Route::redirect('home', '/dashboard');


Route::group(['middleware' => ['web'], 'namespace' => config('sap.controller_namespace')], function () {
    if (!config('sap.hidden_auth_route_names.logout', false)) {
        Route::match(['post'], 'logout', 'Auth\LoginController@logout')->name('logout');
    }
    if (!config('sap.hidden_auth_route_names.password_email', false)) {
        Route::match(['post'], 'password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    }
    if (!config('sap.hidden_auth_route_names.password_update', false)) {
        Route::match(['post'], 'password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');
    }
    if (!config('sap.hidden_auth_route_names.password_request', false)) {
        Route::match(['get', 'head'], 'password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
    }
    if (!config('sap.hidden_auth_route_names.password_reset', false)) {
        Route::match(['get', 'head'], 'password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
    }

    Route::post('editor/upload/image', function (Request $request) {
        $url = '';
        if ($request->file('image')->isValid()) {
            $url = asset(
                str_replace(
                    'public',
                    'storage',
                    $request->file('image')
                        ->storeAs(
                            'public/editor',
                            Str::uuid() . '.' . $request->file('image')->extension()
                        )
                )
            );
        }
        return response()->json(compact('url'));
    })->name('editor.upload_image');

    Route::group(['middleware' => ['guest']], function () {
        if (!config('sap.hidden_auth_route_names.login', false)) {
            Route::match(['get', 'head'], 'login', 'Auth\LoginController@showLoginForm')->name('login');
        }
        Route::match(['post'], 'login', 'Auth\LoginController@login');
        if (!config('sap.hidden_auth_route_names.register', false)) {
            Route::match(['get', 'head'], 'register', 'Auth\RegisterController@showRegistrationForm')->name('register');
        }
        Route::match(['post'], 'register', 'Auth\RegisterController@register');
    });

    Route::group(['middleware' => ['auth', 'can:Access Admin Panel']], function () {
        if (!config('sap.hidden_auth_route_names.password_confirm', false)) {
            Route::match(['get', 'head'], 'password/confirm', 'Auth\ConfirmPasswordController@showConfirmForm')->name('password.confirm');
        }
        Route::match(['post'], 'password/confirm', 'Auth\ConfirmPasswordController@confirm');
        if (!config('sap.hidden_auth_route_names.logout', false)) {
            Route::get('logout', 'Auth\LoginController@logout')->name('logout');
        }
        Route::impersonate();
    });
});
