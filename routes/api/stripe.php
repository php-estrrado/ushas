<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Customer\StripeWebhookController;
Route::stripeWebhooks('stripe-webhooks');
Route::post('/customer/webhooks/stripe', [App\Http\Controllers\Api\Customer\StripeWebhookController::class, 'handleWebhook']);
