<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\TransactionController;
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
    Route::get('me', [AuthController::class, 'me']);
    Route::post('/register', App\Http\Controllers\Api\RegisterController::class)->name('register');

});

Route::group([
    'middleware' => 'auth:api'
], function ($router) {
    Route::get('/categories', [CategoriesController::class, 'index']);
    Route::get('/category/{id}', [CategoriesController::class, 'getSingleCategory']);
    Route::post('/category', [CategoriesController::class, 'create']);
    Route::delete('/category/{id}', [CategoriesController::class, 'delete']);
    Route::patch('/category/{id}', [CategoriesController::class, 'update']);

    Route::get('/wallets', [WalletController::class, 'index']);
    Route::get('/wallet/{id}', [WalletController::class, 'getSingleWallet']);
    Route::post('/wallet', [WalletController::class, 'create']);
    Route::delete('/wallet/{id}', [WalletController::class, 'delete']);
    Route::patch('/wallet/{id}', [WalletController::class, 'update']);
    Route::get('/wallet/{id}/transactions', [WalletController::class, 'getTransactions']);
    
    Route::get('/transactions', [TransactionController::class, 'index']);
    Route::get('/transaction/{id}', [TransactionController::class, 'getById']);
    Route::delete('/transaction/{id}', [TransactionController::class, 'delete']);
    Route::patch('/transaction/{id}', [TransactionController::class, 'update']);
    Route::post('/transaction', [TransactionController::class, 'create']);

});

Route::get('/tes', [CategoriesController::class, 'test']);
Route::get('/tes2', [TransactionController::class, 'index']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
