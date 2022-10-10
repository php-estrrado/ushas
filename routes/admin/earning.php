<?php

use Illuminate\Support\Facades\Route;

Route::get('/admin/seller/earnings', [App\Http\Controllers\Admin\SellerEarningController::class, 'earnings']);
Route::get('/admin/seller/earnings/{id}', [App\Http\Controllers\Admin\SellerEarningController::class, 'earnings']);
Route::get('/admin/seller/settlements', [App\Http\Controllers\Admin\SellerEarningController::class, 'settlements']);
Route::get('/admin/seller/settlements/{id}', [App\Http\Controllers\Admin\SellerEarningController::class, 'settlements']);
Route::get('/admin/seller/payment/{id}', [App\Http\Controllers\Admin\SellerEarningController::class, 'payment']);


Route::post('/admin/seller/settlement/save', [App\Http\Controllers\Admin\SellerEarningController::class, 'saveSettltment']);


Route::get('/admin/seller/bulk-settlements', [App\Http\Controllers\Admin\SellerEarningController::class, 'bulk_settlements']);
Route::get('/admin/seller/bulk-settlements/{id}', [App\Http\Controllers\Admin\SellerEarningController::class, 'bulk_settlements']);
Route::post('/admin/bulk-settlements/filter', [App\Http\Controllers\Admin\SellerEarningController::class, 'bulkFilter']);
Route::post('/admin/seller/bulk-settlements/save', [App\Http\Controllers\Admin\SellerEarningController::class, 'saveBulkSettltment']);

Route::post('/admin/seller/earnings-filter/', [App\Http\Controllers\Admin\SellerEarningController::class, 'earnings_filter']);
