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
    return redirect('categories/index');
});


Route::controller(\App\Http\Controllers\CustomerController::class)
    ->prefix('customers')
    ->name('customers.')
    ->group(function (){
        Route::get('index','index')->name('index');
        Route::get('getCustomers','getCustomers')->name('getCustomers');
        Route::post('add','add')->name('add');
        Route::match(['get','post'],'edit/{id}','edit')->name('edit');
        Route::delete('delete/{id}','delete')->name('delete');
    });


Route::controller(\App\Http\Controllers\CategoriesController::class)
    ->prefix('categories')
    ->name('categories.')
    ->group(function (){
        Route::get('index','index')->name('index');
        Route::get('getCategories','getCategories')->name('getCategories');
        Route::post('add','add')->name('add');
        Route::match(['get','post'],'edit/{id}','edit')->name('edit');
        Route::delete('delete/{id}','delete')->name('delete');
    });

Route::controller(\App\Http\Controllers\ProductsController::class)
    ->prefix('products')
    ->name('products.')
    ->group(function (){
        Route::get('index','index')->name('index');
        Route::get('getProducts','getProducts')->name('getProducts');
        Route::post('add','add')->name('add');
        Route::match(['get','post'],'edit/{id}','edit')->name('edit');
        Route::delete('delete/{id}','delete')->name('delete');
    });


Route::controller(\App\Http\Controllers\AddressesController::class)
    ->prefix('addresses')
    ->name('addresses.')
    ->group(function (){
        Route::get('index','index')->name('index');
        Route::get('getAddresses','getAddresses')->name('getAddresses');
        Route::post('add','add')->name('add');
        Route::match(['get','post'],'edit/{id}','edit')->name('edit');
        Route::delete('delete/{id}','delete')->name('delete');
    });

Route::controller(\App\Http\Controllers\Customer_paymentsController::class)
    ->prefix('payments')
    ->name('payments.')
    ->group(function (){
        Route::get('index','index')->name('index');
        Route::get('getPayment','getPayment')->name('getPayment');
        Route::post('add','add')->name('add');
        Route::match(['get','post'],'edit/{id}','edit')->name('edit');
        Route::delete('delete/{id}','delete')->name('delete');
    });

Route::controller(\App\Http\Controllers\Order_detailsController::class)
    ->prefix('order_details')
    ->name('order_details.')
    ->group(function (){
        Route::get('index','index')->name('index');
        Route::get('getOrder_details','getOrder_details')->name('getOrder_details');
        Route::post('add','add')->name('add');
        Route::match(['get','post'],'edit/{id}','edit')->name('edit');
        Route::delete('delete/{id}','delete')->name('delete');
    });