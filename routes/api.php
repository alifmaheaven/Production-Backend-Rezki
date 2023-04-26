<?php

use App\Http\Controllers\api\WishesController;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\User_Active_Controller;
use App\Http\Controllers\api\User_Bank_Controller;
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


//wishes
Route::controller(WishesController::class)->group(function () {
    Route::get('/wishes', 'index');
    Route::post('/wishes', 'store');
    Route::get('/wishes/{id}', 'show_detail');
    Route::put('/wishes/{id}', 'update');
    Route::delete('/wishes/{id}', 'destroy');
});

//user_active
Route::controller(User_Active_Controller::class)->group(function(){
    Route::get('/user_active', 'index');
});

//user_bank
Route::controller(User_Bank_Controller::class)->group(function(){
    Route::get('/user_bank', 'index');
});