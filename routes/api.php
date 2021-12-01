<?php

use App\Http\Controllers\SellerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\CardController;

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
Route::middleware(['verifyrequest'])->group(function(){

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
        
        Route::prefix('/user')->group(function(){

            /**
             * Adres işlemleri
             */
            Route::prefix('/address')->group(function(){
                Route::post('/add', [AddressController::class, 'create_address']);
                Route::post('/update', [AddressController::class, 'update_address']);
                Route::post('/delete', [AddressController::class, 'delete_address']);
                Route::get('/get', [AddressController::class, 'get']);
            });

            /**
             * Kart işlemleri
             */
            Route::prefix('/card')->group(function(){
                Route::post('/add', [CardController::class, 'create_card']);
                Route::post('/update', [CardController::class, 'update_card']);
                Route::post('/delete', [CardController::class, 'delete_card']);
                Route::get('/get', [CardController::class, 'get']);
            });

        });

        /**
         * Satıcı,Yönetici itekleri için
         */ 
        Route::prefix('/seller')->middleware(['seller'])->group(function () {

            /**
             * Oluşturma işlemleri
             */
            Route::post('create-product', [SellerController::class, 'create_product']);
            Route::post('create-category', [SellerController::class, 'create_category']);
            Route::post('create-discount', [SellerController::class, 'create_discount']);
        });

    });

});
