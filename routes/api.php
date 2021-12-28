<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
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

Route::group(['prefix' => 'users'], function() {
    Route::post('/register', [UserController::class, 'register']);
    Route::group(['middleware' => ['jwt.user']], function() {
       
    });
});

Route::group(['prefix' => 'admins'], function() {
    Route::post('/register', [AdminController::class, 'register']);
    Route::post('/login', [AdminController::class, 'login']);
    

    Route::group(['middleware' => ['jwt.admin']], function() {
        Route::post('/logout', [AdminController::class, 'logout']);
        Route::get('/getAverage', [AdminController::class, 'getAverage']);
        Route::get('/filter', [AdminController::class, 'filter']);
        Route::get('/countUsers', [AdminController::class, 'CountUsers']);
    });
});
