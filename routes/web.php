<?php

use App\Http\Controllers\BargainController;
use App\Http\Controllers\DiscountController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OAuthController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\PriceBeatController;
use App\Http\Controllers\Product\ProductGroupController;

Route::group(['middleware' => ['shopify.auth']], function () {
    Route::post('product-group', [ProductGroupController::class, 'create']);
    Route::post('product/{group}', [ProductGroupController::class, 'addProduct']);
    Route::post('bargain', [BargainController::class, 'create']);
    Route::delete('bargain/{bargain}', [BargainController::class, 'delete']);
    Route::post('price-beat-offer', [PriceBeatController::class, 'create']);

    // Offers Routes
    Route::get('offers', [OfferController::class, 'getAllOffers']);
    Route::get('offers/{offer}', [OfferController::class, 'getOffer']);
    Route::get('offer-accept/{offer}', [OfferController::class, 'acceptOffer']);
    Route::get('offer-deny/{offer}', [OfferController::class, 'denyOffer']);
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
