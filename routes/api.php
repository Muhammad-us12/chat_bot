<?php

use App\Http\Controllers\OfferController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebhookController;

Route::post('/webhooks', WebhookController::class)->name('webhook.callback');
Route::post('/offer', [OfferController::class,'newOffer'])->name('offer.create');