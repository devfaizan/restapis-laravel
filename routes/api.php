<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/checkconnection', [UserController::class, 'checkConnection']);
Route::post('/users', [UserController::class, 'create']);
Route::get('/getuserdata', [UserController::class, 'getUserData']);
Route::post('/edituser/{id}', [UserController::class, 'editUserData']);
Route::post('/deletebyuserid', [UserController::class, 'deleteByUserID']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//  php artisan serve --host 192.168.18.18 --port 80