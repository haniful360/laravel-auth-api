<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgetPasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// single route


Route::post('/register', [AuthController::class, 'register']);


Route::prefix('api')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/send-password-reset', [ForgetPasswordController::class, 'send_password_reset']);
    Route::post('reset-password/{token}', [ForgetPasswordController::class, 'reset']);

    // Route::post('verify/{token}', [ForgetPasswordController::class, 'reset']);
});


// protected route

Route::middleware(['auth:sanctum'])->group(function(){

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/loggedUser', [AuthController::class, 'loggedUser']);

});
