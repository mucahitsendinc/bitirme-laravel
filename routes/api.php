<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\SellerController;

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

        Route::get('products',[ProductController::class, 'get']);
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
                Route::post('/add', [AddressController::class, 'create']);
                Route::post('/update', [AddressController::class, 'update']);
                Route::post('/delete', [AddressController::class, 'delete']);
                Route::get('/get', [AddressController::class, 'get']);
            });

            /**
             * Kart işlemleri
             */
            Route::prefix('/card')->group(function(){
                Route::post('/add', [CardController::class, 'create']);
                Route::post('/update', [CardController::class, 'update']);
                Route::post('/delete', [CardController::class, 'delete']);
                Route::get('/get', [CardController::class, 'get']);
            });

        });

        /**
         * Satıcı,Yönetici itekleri için
         */ 
        Route::prefix('/seller')->middleware(['seller'])->group(function () {

            /**
             * Ürün işlemleri
             */
            Route::prefix('/product')->group(function(){
                Route::post('/add', [ProductController::class, 'create']);
                Route::post('/update', [ProductController::class, 'update']);
                Route::post('/delete', [ProductController::class, 'delete']);
            });

            /**
             * Kategori işemleri
             */
            Route::prefix('/category')->group(function(){
                Route::post('/add', [CategoryController::class, 'create']);
                Route::post('/update', [CategoryController::class, 'update']);
                Route::post('/delete', [CategoryController::class, 'delete']);
            });

            /**
             * İndirim işlemleri
             */
            Route::prefix('/discount')->group(function(){
                Route::post('/add', [DiscountController::class, 'create']);
                Route::post('/update', [DiscountController::class, 'update']);
                Route::post('/delete', [DiscountController::class, 'delete']);
            });
        });

    });

});
