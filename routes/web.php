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

// Auth
Route::get('login')->name('login')->uses('Auth\LoginController@showLoginForm')->middleware('guest');
Route::post('login')->name('login.attempt')->uses('Auth\LoginController@login')->middleware('guest');
Route::post('logout')->name('logout')->uses('Auth\LoginController@logout');

// Dashboard
Route::get('/')->name('dashboard')->uses('DashboardController')->middleware('auth');

// Users
Route::get('users')->name('users')->uses('UsersController@index')->middleware('remember', 'auth');
Route::get('users/create')->name('users.create')->uses('UsersController@create')->middleware('auth');
Route::post('users')->name('users.store')->uses('UsersController@store')->middleware('auth');
Route::get('users/{user}/edit')->name('users.edit')->uses('UsersController@edit')->middleware('auth');
Route::put('users/{user}')->name('users.update')->uses('UsersController@update')->middleware('auth');
Route::delete('users/{user}')->name('users.destroy')->uses('UsersController@destroy')->middleware('auth');
Route::put('users/{user}/restore')->name('users.restore')->uses('UsersController@restore')->middleware('auth');

// Images
Route::get('/img/{path}', 'ImagesController@show')->where('path', '.*');

// Customers
Route::get('customers')->name('customers')->uses('CustomersController@index')->middleware('remember', 'auth');
Route::get('customers/create')->name('customers.create')->uses('CustomersController@create')->middleware('auth');
Route::post('customers')->name('customers.store')->uses('CustomersController@store')->middleware('auth');
Route::get('customers/{customer}/edit')->name('customers.edit')->uses('CustomersController@edit')->middleware('auth');
Route::put('customers/{customer}')->name('customers.update')->uses('CustomersController@update')->middleware('auth');
Route::delete('customers/{customer}')->name('customers.destroy')->uses('CustomersController@destroy')->middleware('auth');
Route::put('customers/{customer}/restore')->name('customers.restore')->uses('CustomersController@restore')->middleware('auth');

// Invoices
Route::get('invoices')->name('invoices')->uses('InvoicesController@index')->middleware('remember', 'auth');
Route::get('invoices/create')->name('invoices.create')->uses('InvoicesController@create')->middleware('auth');
Route::post('invoices')->name('invoices.store')->uses('InvoicesController@store')->middleware('auth');
Route::get('invoices/{invoice}/edit')->name('invoices.edit')->uses('InvoicesController@edit')->middleware('auth');
Route::put('invoices/{invoice}')->name('invoices.update')->uses('InvoicesController@update')->middleware('auth');
Route::delete('invoices/{invoice}')->name('invoices.destroy')->uses('InvoicesController@destroy')->middleware('auth');
Route::put('invoices/{invoice}/restore')->name('invoices.restore')->uses('InvoicesController@restore')->middleware('auth');

