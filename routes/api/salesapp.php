<?php

use Illuminate\Support\Facades\Route;

Route::post('/salesapp/offer/list', [App\Http\Controllers\Api\Salesapp\SalesAppController::class, 'offerlist']);
Route::post('/salesapp/offer/allocate', [App\Http\Controllers\Api\Salesapp\SalesAppController::class, 'offer_allocate']);
Route::post('/salesapp/product/list', [App\Http\Controllers\Api\Salesapp\SalesAppController::class, 'product_list']);