<?php

use App\Http\Controllers\BargainController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OAuthController;
use App\Http\Controllers\Product\ProductGroupController;

Route::group(['middleware' => ['shopify.auth']], function () {
    Route::post('create-product-group', [ProductGroupController::class, 'create']);
    Route::post('add-product/{group}', [ProductGroupController::class, 'addProduct']);
    Route::post('create-bargain', [BargainController::class, 'create']);
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