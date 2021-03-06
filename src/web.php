<?php

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => config('sap.custom_admin_path'), 'middleware' => ['web'], 'namespace' => config('sap.controller_namespace')], function () {
    if (!config('sap.hidden_auth_route_names.logout', false)) {
        Route::match(['post', 'get'], 'logout', 'Auth\LoginController@logout')->name('logout');
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

    Route::group(['middleware' => ['auth_admin', 'can:Access Admin Panel']], function () {
        if (!config('sap.hidden_auth_route_names.password_confirm', false)) {
            Route::match(['get', 'head'], 'password/confirm', 'Auth\ConfirmPasswordController@showConfirmForm')->name('password.confirm');
        }
        Route::match(['post'], 'password/confirm', 'Auth\ConfirmPasswordController@confirm');
        if (!config('sap.hidden_auth_route_names.logout', false)) {
            Route::get('logout', 'Auth\LoginController@logout')->name('logout');
        }
        Route::match(['post'], 'editor/upload/image', function (Request $request) {
            $url = '';
            if ($request->file('image')->isValid()) {
                $url = asset(
                    str_replace(
                        'public',
                        'storage',
                        $request->file('image')
                            ->storeAs(
                                'public/editor',
                                Str::uuid().'.'.$request->file('image')->extension()
                            )
                    )
                );
            }

            return response()->json(compact('url'));
        })->name('editor.upload_image');

        Route::match(['get'], '/search', 'Admin\GlobalSearchController@index')->name('global.search');
        Route::match(['post'], '/search/suggest', 'Admin\GlobalSearchController@suggest')->name('global.suggest');

        Route::get('builder', function () {
            return view('sap::admin.builder.index');
        });
        // ReAuth
        Route::match(['get'], 'reauth', 'Auth\ReauthController@reauth')->name('reauth');
        Route::match(['post'], 'reauth', 'Auth\ReauthController@processReauth')->name('reauth.confirm');

        Route::group(['middleware' => ['reauth_admin']], function () {
            Route::impersonate();
        });
    });
});
