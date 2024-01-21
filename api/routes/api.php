<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\UsersController;

// use App\Http\Controllers\UsersController;

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

// Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::post('auth/login', [AuthController::class, 'login'])->name('auth.login');
Route::post('/signup', [AuthController::class, 'signup'])->name('signup');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('/user', UsersController::class);
    Route::apiResource('/expense', ExpenseController::class);
});
