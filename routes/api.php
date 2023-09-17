<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ErrorController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::get('unAuth', [ErrorController::class, 'unAuth'])->name('unAuth');
Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
    Route::post('/register', App\Http\Controllers\Api\RegisterController::class)->name('register');

});

Route::group([
    'middleware' => 'auth:api'
], function ($router) {
    Route::get('/categories', [CategoriesController::class, 'index']);
    Route::post('/category', [CategoriesController::class, 'create']);
    Route::delete('/category/{id}', [CategoriesController::class, 'delete']);

});

Route::get('/tes', [CategoriesController::class, 'test']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
