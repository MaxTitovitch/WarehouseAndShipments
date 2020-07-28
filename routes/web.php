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
    Route::get('/products', 'AdminController@products')->name('products');
    Route::get('/orders', 'AdminController@orders')->name('orders');
    Route::get('/users', 'AdminController@users')->middleware('authorisation')->name('users');
    Route::post('/parse', 'ImportController@parse')->name('parse');
});

Route::group(['middleware' => 'auth', 'prefix' => 'api'], function () {
    Route::group(['middleware' => 'authorisation'], function () {
        Route::resource('user', 'UserController', ['only' => ['index', 'show', 'update', 'destroy']]);
        Route::resource('product', 'ProductController', ['only' => ['index', 'show', 'store', 'update']]);
    });
    Route::resource('shipment', 'ShipmentController', ['only' => ['show', 'store', 'update']]);
    Route::resource('order', 'OrderController', ['only' => ['show', 'store', 'update', 'destroy']]);
    Route::post('/order/{order}', 'OrderController@copy')->name('order.copy');
    Route::get('/chart-data', 'AdminController@chartData')->name('chart-data');
});