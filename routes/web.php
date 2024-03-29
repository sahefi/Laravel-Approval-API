<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/




// routes/web.php


Route::group(['prefix' => 'auth'], function () {
    // ... rute lainnya ...
    Route::get('login', [AuthController::class, 'index']);
    Route::post('login', [AuthController::class, 'login']);

});



