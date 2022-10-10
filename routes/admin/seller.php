<?php

use Illuminate\Support\Facades\Route;

Route::get('/admin/sellers', [App\Http\Controllers\Admin\SellerController::class, 'sellers']);
Route::get('/admin/seller/{id}', [App\Http\Controllers\Admin\SellerController::class, 'seller']);
Route::get('/admin/new-sellers', [App\Http\Controllers\Admin\SellerController::class, 'newSellers']);
Route::post('/admin/new-sellers', [App\Http\Controllers\Admin\SellerController::class, 'newSellersFilter']);
Route::get('/admin/seller/{id}/{type}', [App\Http\Controllers\Admin\SellerController::class, 'seller']);

Route::post('/admin/sellers', [App\Http\Controllers\Admin\SellerController::class, 'sellers']);
Route::post('/admin/seller/validate', [App\Http\Controllers\Admin\SellerController::class, 'validateSeller']);
Route::post('/admin/seller/save', [App\Http\Controllers\Admin\SellerController::class, 'saveSeller']);
Route::post('/admin/seller/updateStatus', [App\Http\Controllers\Admin\SellerController::class, 'updateStatus']);


Route::post('/admin/seller/bank-data', [App\Http\Controllers\Admin\SellerController::class, 'saveSellerBank']);
Route::post('/admin/seller/updateServiceStatus', [App\Http\Controllers\Admin\SellerController::class, 'updateServiceStatus']);