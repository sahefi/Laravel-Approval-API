<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BookingController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\VehicleController;
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


Route::group(['middleware' => 'api','prefix' => 'auth'], function ($router) {
    Route::post('register',[AuthController::class,'register']);
    Route::post('login',[AuthController::class,'login']);
    Route::post('logout',[AuthController::class,'logout']);
});

// Route::group(['middleware'=>['jwt.auth']],function(){
    Route::get('role',[RoleController::class,'index']);
    Route::post('role',[RoleController::class,'store']);
    Route::patch('role',[RoleController::class,'update']);
    Route::delete('role',[RoleController::class,'destroy']);
// });

// Route::group(['middleware'=>['jwt.auth']],function(){
    Route::get('user',[UserController::class,'index']);
    Route::get('user/id',[UserController::class,'show']);
    Route::patch('user',[UserController::class,'update']);
    Route::delete('user',[UserController::class,'destroy']);

// });

// Route::group(['middleware'=>['jwt.auth']],function(){
    Route::get('vehicle',[VehicleController::class,'index']);
    Route::post('vehicle',[VehicleController::class,'store']);
    Route::patch('user',[UserController::class,'update']);
    Route::delete('user',[UserController::class,'destroy']);

// });

Route::get('booking',[BookingController::class,'approve']);
Route::post('booking',[BookingController::class,'store']);
Route::patch('user',[UserController::class,'update']);
Route::delete('user',[UserController::class,'destroy']);


