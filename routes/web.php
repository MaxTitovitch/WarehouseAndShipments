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

Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', 'AdminController@index')->name('home');
    Route::get('/inbound-shipments', 'AdminController@inboundShipments')->name('inbound-shipments');
    Route::get('/users', 'AdminController@users')->name('users');
    Route::get('/products', 'AdminController@products')->name('products');
    Route::get('/orders', 'AdminController@orders')->name('orders');
});
