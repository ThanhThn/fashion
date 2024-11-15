<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
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

Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home');

Route::group(['middleware' => 'auth'], function () {
    Route::get("dashboard", function() {
        return view("dashboard");
    })->name("dashboard");
	Route::resource('user', 'App\Http\Controllers\UserController', ['except' => ['show']]);
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\ProfileController@update']);
	Route::get('upgrade', function () {return view('pages.upgrade');})->name('upgrade');
	 Route::get('map', function () {return view('pages.maps');})->name('map');
	 Route::get('icons', function () {return view('pages.icons');})->name('icons');
	 Route::get('table-list', function () {return view('pages.tables');})->name('table');
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']);

    Route::group(['prefix' => '/product',], function () {
        Route::get('/', 'App\Http\Controllers\ProductController@view')->name('product');
        Route::get('/create', 'App\Http\Controllers\ProductController@create')->name('product.create');
        Route::post('/create', 'App\Http\Controllers\ProductController@add')->name('product.add');
    });

    Route::group(['prefix' => '/category',], function () {
        Route::get('/', 'App\Http\Controllers\CategoryController@view')->name('category');
        Route::get('/create', 'App\Http\Controllers\CategoryController@create')->name('category.create');
        Route::post("create", 'App\Http\Controllers\CategoryController@add')->name('category.add');
    });

});

