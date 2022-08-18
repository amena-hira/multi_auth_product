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

Auth::routes();
Route::middleware(['isAdmin'])->group(function () { 
    Route::get('/admin/home', 'HomeController@admin_index')->name('admin.home');    
});
// Route::get('/home', 'HomeController@index')->name('home');
Route::middleware(['isCustomer'])->group(function () {   
    Route::get('/home', 'HomeController@index')->name('home');
});
Route::middleware(['isSeller'])->group(function () {   
    Route::get('/seller/home', 'HomeController@seller_index')->name('seller.home');
    Route::get('/seller/product', 'ProductController@index')->name('seller.product');
    Route::get('/seller/data_show', 'ProductController@show')->name('seller.data_show');
    Route::post('/seller/add_product', 'ProductController@store')->name('seller.add_product');
    Route::get('/seller/edit_product/{id}', 'ProductController@edit')->name('seller.edit_product');
    Route::post('/seller/update_product/{id}', 'ProductController@update')->name('seller.update_product');
    Route::get('/seller/delete_product/{id}', 'ProductController@destroy')->name('seller.delete_product');
});

