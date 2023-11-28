<?php

use App\Http\Controllers\OfferController;
use App\Http\Controllers\OfferControllerGraphQl;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebhookController;

Route::post('/webhooks', WebhookController::class)->name('webhook.callback');
Route::post('/offer', [OfferController::class,'newOffer'])->name('offer.create');
Route::post('/offer-graphQl', [OfferControllerGraphQl::class,'newOffer']);