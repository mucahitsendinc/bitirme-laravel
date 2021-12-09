<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\WarrantController;

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
Route::middleware(['verifyrequest','cors'])->group(function(){

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
        Route::get('categories',[CategoryController::class, 'get']);
        Route::get('products/{id}',[ProductController::class, 'detail']);
        Route::get('discover',[ProductController::class, 'discover']);
    });

    /**
     * Oturum açılmış kullanıcı istekleri
     */

    Route::middleware(['auth'])->group(function(){

        Route::post('resend-activation-code', [UserController::class, 'new_email_verify_code']);
        
        Route::prefix('/user')->middleware(['active'])->group(function(){

            Route::get('/get', [UserController::class, 'get']);
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

            Route::prefix('/cart')->group(function(){
                Route::post('/add', [CartController::class, 'add']);
                Route::post('/update', [CartController::class, 'update']);
                Route::post('/delete', [CartController::class, 'delete']);
                Route::post('/increment', [CartController::class, 'increment']);
                Route::post('/decrement', [CartController::class, 'decrement']);
                Route::post('/extraction', [CartController::class, 'extraction']);
                Route::get('/get', [CartController::class, 'get']);
            });

        });

        /**
         * Satıcı,Yönetici itekleri için
         */ 
        Route::prefix('/seller')->middleware(['seller'])->group(function () {

            /**
             * Fotoğraf işlemleri
             */
            Route::prefix('/image')->group(function(){
                Route::post('/add', [ImageController::class, 'upload']);
                Route::post('/delete', [ImageController::class, 'delete']);
                Route::get('/get', [ImageController::class, 'get']);
            });

            /**
             * Galeri işlemleri
             */
            Route::prefix('/gallery')->group(function(){

                Route::post('/add', [GalleryController::class, 'create']);
                Route::post('/update', [GalleryController::class, 'update']);
                Route::post('/delete', [GalleryController::class, 'delete']);
                Route::get('/get', [GalleryController::class, 'get']);

                /**
                 * Galeri fotoğraf işlemleri
                 */
                Route::prefix('/image')->group(function(){
                    Route::post('/add', [GalleryController::class, 'image_add']);
                    Route::post('/delete', [GalleryController::class, 'image_delete']);
                });

            });

            /**
             * Ürün işlemleri
             */
            Route::prefix('/product')->group(function(){

                /**
                 * Ürün fotoğraf işlemleri
                 */
                Route::prefix('/image')->group(function(){
                    Route::post('/add', [ImageController::class, 'upload_product_image']);
                    Route::post('/delete', [ImageController::class, 'delete_product_image']);
                    Route::get('/get', [ImageController::class, 'get_product_images']);
                });

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

            /**
             * Garanti işlemleri
             */
            Route::prefix('/warranty')->group(function(){
                Route::post('/add', [OfferController::class, 'create']);
                Route::post('/update', [OfferController::class, 'update']);
                Route::post('/delete', [OfferController::class, 'delete']);
                Route::get('/get',[WarrantController::class, 'get']);
            });

            /**
             * Birim işlemleri
             */
            Route::prefix('/unit')->group(function () {
                Route::post('/add', [UnitController::class, 'create']);
                Route::post('/update', [UnitController::class, 'update']);
                Route::post('/delete', [UnitController::class, 'delete']);
                Route::get('/get', [UnitController::class, 'get']);
            });

            /**
             * Kampanya işlemleri
            */
            Route::prefix('/offer')->group(function(){
                
                Route::prefix('/user')->group(function(){
                    Route::post('/add', [OfferController::class, 'create_user_offer']);
                    Route::post('/update', [OfferController::class, 'update_user_offer']);
                    Route::post('/delete', [OfferController::class, 'delete_user_offer']);
                });

                Route::prefix('/product')->group(function () {
                    Route::post('/add', [OfferController::class, 'create_product_offer']);
                    Route::post('/update', [OfferController::class, 'update_product_offer']);
                    Route::post('/delete', [OfferController::class, 'delete_product_offer']);
                });

                Route::prefix('/category')->group(function () {
                    Route::post('/add', [OfferController::class, 'create_category_offer']);
                    Route::post('/update', [OfferController::class, 'update_category_offer']);
                    Route::post('/delete', [OfferController::class, 'delete_category_offer']);
                });
                
            });
        });

    });

});