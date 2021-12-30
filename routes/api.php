<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');



Route::get('/products','App\Http\Controllers\ProductController@index');
Route::get('/products/{name}','App\Http\Controllers\ProductController@search');

Route::get('/categories','App\Http\Controllers\CategoryController@index');


Route::middleware(['auth:api'])->group(function ()
{

    Route::get('/products/{id}','App\Http\Controllers\ProductController@show');
    Route::post('/products','App\Http\Controllers\ProductController@store');
    Route::post('/products/{id}','App\Http\Controllers\ProductController@update');
    Route::post('/products/{id}','App\Http\Controllers\ProductController@destroy');


    Route::get('/categories/{id}','App\Http\Controllers\CategoryController@show');
    Route::post('/categories','App\Http\Controllers\CategoryController@store');
    Route::post('/categories/{id}','App\Http\Controllers\CategoryController@update');
        Route::post('/categories/{id}','App\Http\Controllers\CategoryController@destroy');


});






//    Route::get('/products', [ProductController::class, 'index']);
////    Route::get('products', [AuthController::class, 'products'])->name('products');
////    Route::post('products', [AuthController::class, 'products'])->name('products');
////    Route::post('products', [AuthController::class, 'products'])->name('products');
////    Route::post('products', [AuthController::class, 'products'])->name('products');




