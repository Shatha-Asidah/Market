<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LikeController;

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

// auth routes
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');


// without middleware
Route::get('/products','App\Http\Controllers\ProductController@index');
Route::get('/products/sort','App\Http\Controllers\ProductController@sorting');
Route::get('/products/{id}','App\Http\Controllers\ProductController@show');
Route::get('/products/search/{name}','App\Http\Controllers\ProductController@search');
Route::get('/categories','App\Http\Controllers\CategoryController@index');
Route::get('/categories/{id}','App\Http\Controllers\CategoryController@show');




Route::middleware(['auth:api'])->group(function ()
{


    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
 // categories routes
 //   Route::get('/categories/{id}','App\Http\Controllers\CategoryController@show');
    Route::post('/categories','App\Http\Controllers\CategoryController@store');
    Route::post('/categories/{id}','App\Http\Controllers\CategoryController@update');
    Route::post('/categories/d/{id}','App\Http\Controllers\CategoryController@destroy');



//      products routes
      Route::prefix('products')->group(function (){

        Route::post('/', [ProductController::class, 'store']);
       // Route::get('/{id}', [ProductController::class, 'show']);
        Route::post('/update/{id}', [ProductController::class, 'update']);
        Route::post('/{id}', [ProductController::class, 'destroy']);

       //  comments routes
        Route::prefix("/{id}/comments")->group(function (){
            Route::get('/', [CommentController::class, 'index']);
            Route::post('/', [CommentController::class, 'store']);
            Route::post('/update/{comment}', [CommentController::class, 'update']);
            Route::post('/{comment}', [CommentController::class, 'destroy']);
        });


        // likes routes
        Route::prefix("/{id}/likes")->group(function (){
            Route::get('/', [LikeController::class, 'index']);
            Route::post('/', [LikeController::class, 'store']);

        });
    });
});






