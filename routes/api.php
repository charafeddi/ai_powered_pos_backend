<?php

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('login', 'App\Http\Controllers\AuthController@login');
    Route::post('register', 'App\Http\Controllers\AuthController@register');
    Route::post('logout', 'App\Http\Controllers\AuthController@logout');
    Route::post('refresh', 'App\Http\Controllers\AuthController@refresh');
    Route::get('user-profile', 'App\Http\Controllers\AuthController@userProfile');
});

Route::get('articles', 'App\Http\Controllers\ArticleController@index');
Route::get('articles/{article}', 'App\Http\Controllers\ArticleController@show');
Route::post('articles', 'App\Http\Controllers\ArticleController@store');
Route::put('articles/{article}', 'App\Http\Controllers\ArticleController@update');
Route::delete('articles/{article}', 'App\Http\Controllers\ArticleController@delete');

//Product
Route::get('products/{user_id}', 'App\Http\Controllers\ProductController@index');
Route::get('products/{id}/show', 'App\Http\Controllers\ProductController@show');
Route::put('products/{id}', 'App\Http\Controllers\ProductController@update');
Route::post('products', 'App\Http\Controllers\ProductController@store');
Route::delete('products/{id}', 'App\Http\Controllers\ProductController@delete');


//Client
Route::get('client/{user_id}', 'App\Http\Controllers\ClientController@index');
Route::get('client/{id}/show', 'App\Http\Controllers\ClientController@show');
Route::put('client/{id}', 'App\Http\Controllers\ClientController@update');
Route::post('client', 'App\Http\Controllers\ClientController@store');

// supplier 
Route::get('supplier/{user_id}','App\Http\Controllers\SupplierController@index');
Route::get('supplier/{id}/show','App\Http\Controllers\SupplierController@show');
Route::put('supplier/{id}', 'App\Http\Controllers\SupplierController@update');
Route::post('supplier', 'App\Http\Controllers\SupplierController@store');
Route::delete('supplier/{id}', 'App\Http\Controllers\SupplierController@delete');


// ProductType 
Route::get('ProductType','App\Http\Controllers\ProductTypeController@index');
Route::get('ProductType/{id}/show','App\Http\Controllers\ProductTypeController@show');

//Sales
Route::get('sales/{user_id}', 'App\Http\Controllers\SalesController@index');
Route::get('sales/{id}/show', 'App\Http\Controllers\SalesController@show');
Route::put('sales/{id}', 'App\Http\Controllers\SalesController@update');
Route::post('sales', 'App\Http\Controllers\SalesController@store');

//Todo 
Route::get('todo/{user_id}','App\Http\Controllers\TodoController@index');
Route::get('todo/{id}/show','App\Http\Controllers\TodoController@show');
Route::get('todo/{id}/trash','App\Http\Controllers\TodoController@importDeletedTodos');
Route::get('todo/{id}/done','App\Http\Controllers\TodoController@importFinishedTodos');
Route::put('todo/{id}','App\Http\Controllers\TodoController@update');
Route::post('todo/','App\Http\Controllers\TodoController@store');
Route::delete('todo/{id}', 'App\Http\Controllers\TodoController@destroy');
