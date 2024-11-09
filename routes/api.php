<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'client'], function (){
    Route::post("login", [LoginController::class, "loginUser"]);
    Route::post("register", [RegisterController::class, "registerUser"]);
    Route::get('infor', [LoginController::class, "infor"])->middleware('jwt.verify');


    Route::group(['middleware' => 'jwt.verify', 'prefix' => "cart"], function (){
        Route::get('/list', [CartController::class, "listItemsInCart"]);
        Route::post('/add', [CartController::class, "addToCart"]);
        Route::post('/delete', [CartController::class, "removeFromCart"]);
    });
} );

Route::group(['prefix'=>'product'],function(){
    Route::get('list', [ProductController::class, 'listProduct']);
    Route::get('detail/{id}', [ProductController::class, 'detailProduct']);
    Route::get('similar/{id}', [ProductController::class, 'listSimilarProduct']);
});


