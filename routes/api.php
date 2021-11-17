<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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
Route::middleware(['verifyrequest','strange'])->group(function(){
    Route::post('register',[UserController::class,'register']);
    Route::post('register-complete',[UserController::class,'email_verify']);
    Route::post('login',[UserController::class,'login']);
});
