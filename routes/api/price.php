<?php

use Illuminate\Support\Facades\Route;

//Salesprice Type

Route::post('/crm/price/salespricetype_insert', [App\Http\Controllers\Api\Salesprice\PriceController::class, 'salespricetype_insert']);
Route::post('/crm/price/salespricetype_update', [App\Http\Controllers\Api\Salesprice\PriceController::class, 'salespricetype_update']);
Route::post('/crm/price/salespricetype_delete', [App\Http\Controllers\Api\Salesprice\PriceController::class, 'salespricetype_delete']);
Route::post('/crm/price/salespricetype_list', [App\Http\Controllers\Api\Salesprice\PriceController::class, 'salespricetype_list']);


//Salesprice List

Route::post('/crm/price/salespricelist_insert', [App\Http\Controllers\Api\Salesprice\PriceController::class, 'salespricelist_insert']);
Route::post('/crm/price/salespricelist_update', [App\Http\Controllers\Api\Salesprice\PriceController::class, 'salespricelist_update']);
Route::post('/crm/price/salespricelist_delete', [App\Http\Controllers\Api\Salesprice\PriceController::class, 'salespricelist_delete']);
Route::post('/crm/price/salespricelist_list', [App\Http\Controllers\Api\Salesprice\PriceController::class, 'salespricelist_list']);