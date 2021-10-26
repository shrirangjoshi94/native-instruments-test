<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AuthenticationController, ProductController, UserController, UserPurchasedProductsController};

Route::post('auth', [AuthenticationController::class, 'generateAuthToken']);

Route::group(
    ['middleware' => 'auth:sanctum'],
    function () {
        Route::get('products', [ProductController::class, 'index']);

        Route::get('user', [UserController::class, 'loggedInUserDetails']);

        Route::group(
            ['prefix' => 'user'],
            function () {
                Route::get('products', [UserPurchasedProductsController::class, 'index']);
                Route::post('products', [UserPurchasedProductsController::class, 'store']);
                Route::delete('products/{sku}', [UserPurchasedProductsController::class, 'destroy']);
            }
        );
    }
);
