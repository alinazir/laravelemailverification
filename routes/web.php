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

Route::get('/','User@index');
Route::post('Registered','User@Registered');
Route::get('Verify','User@Verify');
Route::get('Autherized/{token}','User@Autherized')->name('EmailAuthentication');
Route::get('LoginUser','User@loginview')->name('loginpage');
Route::post('login','User@login');
Route::get('AccountVerification','User@AccountVerifyError')->name('verifyFirst');
