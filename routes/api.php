<?php

use App\Http\Controllers\SellerController;
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

    /**
     * Oturum açılmadan atılan yabancı istekler
     */

    Route::middleware(['strange'])->group(function(){
        Route::post('register', [UserController::class, 'register']);
        Route::post('activate-account', [UserController::class, 'email_verify']);
        Route::post('login', [UserController::class, 'login']);
        Route::post('logout', [UserController::class, 'logout']);
        Route::post('forgot-password', [UserController::class, 'forgot_password']);
        Route::post('reset-password', [UserController::class, 'reset_password']);
    });

    /**
     * Oturum açılmış kullanıcı istekleri
     */
    Route::middleware(['auth'])->group(function(){
        Route::post('resend-activation-code', [UserController::class, 'new_email_verify_code']);
    });

    /**
     * Satıcı itekleri
     */
    Route::middleware(['seller'])->group(function () {
        Route::post('create-product', [SellerController::class, 'create_product']);
    });


});
