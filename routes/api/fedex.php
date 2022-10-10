<?php

use Illuminate\Support\Facades\Route;

Route::get('/customer/fedex-auth', [App\Http\Controllers\Api\Customer\FedexController::class, 'fedexAuth'])->name('fedexAuth');
Route::post('/customer/stripe-keys', [App\Http\Controllers\Api\Customer\StripeController::class, 'stripeKeys'])->name('stripe.keys');

