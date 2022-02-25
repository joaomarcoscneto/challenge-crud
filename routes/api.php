<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AuthController;
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

Route::controller(AuthController::class)->group(function () {
    Route::post('/login', 'login');
    Route::post('/logout', 'logout');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users/{user}/send-email', [App\Http\Controllers\UserController::class, 'sendUserMail'])->name('users.send.email');
    Route::apiResource('/users', App\Http\Controllers\UserController::class);
});
