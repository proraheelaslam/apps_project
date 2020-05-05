<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/admin/login');
});

Route::group(['prefix' => 'admin','middleware'=>'auth.lock'], function () {
  Route::get('/login', 'AdminAuth\LoginController@showLoginForm')->name('admin.login');
  Route::post('/login', 'AdminAuth\LoginController@login');
  Route::post('/logout', 'AdminAuth\LoginController@logout')->name('admin.logout');

  Route::get('/register', 'AdminAuth\RegisterController@showRegistrationForm')->name('register');
  Route::post('/register', 'AdminAuth\RegisterController@register');

  Route::post('/password/email', 'AdminAuth\ForgotPasswordController@sendResetLinkEmail')->name('password.request');
  Route::post('/password/reset', 'AdminAuth\ResetPasswordController@reset')->name('password.email');
  Route::get('/password/reset', 'AdminAuth\ForgotPasswordController@showLinkRequestForm')->name('password.reset');
  Route::get('/password/reset/{token}', 'AdminAuth\ResetPasswordController@showResetForm');




});


// locked routes
Route::group(['middleware' => ['auth.lock']], function () {
    Route::get('admin/login/locked', 'AdminAuth\LoginController@locked');
    Route::post('admin/login/locked', 'AdminAuth\LoginController@unlock');
});

Route::get('invite','Admin\EventInvitationController@invite');

Route::get('user/password/reset/{id}','Admin\UserResetPasswordController@showResetPassword')->name('reset.password');
Route::post('user/password/reset','Admin\UserResetPasswordController@reset');
