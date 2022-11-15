<?php

use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});

Route::view('login', 'pages.login');
Route::view('register', 'pages.register');
Route::view('user', 'pages.user');
Route::view('account', 'pages.account');
Route::view('dashboard', 'pages.dashboard');
Route::view('about', 'pages.about');

