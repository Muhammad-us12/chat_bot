<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OAuthController;
use App\Http\Controllers\ProductController;


Route::group(['middleware' => ['shopify.auth']], function () {
    Route::get('product-list', [ProductController::class, 'productList']);
});





Route::view('/login', 'login')->name('auth.login');
Route::get('/auth/begin', [OAuthController::class, 'begin'])->name('auth.begin');
Route::get('/auth/callback', [OAuthController::class, 'callback'])->name('auth.callback');
Route::fallback(\App\Http\Controllers\FrontendController::class)->middleware('shopify.installed');

Route::get('/shop-info', function (\Illuminate\Http\Request $request) {
    /** @var \App\Models\Store */
    $store = $request->get('store');

    return $store->shopifyClient()->shopInfo();
})->middleware('shopify.auth');
