<?php

use App\Http\Controllers\Api\{RegisterController, LoginController, ProductController};
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

// Auth
Route::post('login', LoginController::class)->name('login');
Route::post('register', RegisterController::class)->name('register');

Route::apiResource('product', ProductController::class)->middleware('auth:sanctum');
