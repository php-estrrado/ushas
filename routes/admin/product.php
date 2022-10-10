<?php

use Illuminate\Support\Facades\Route;
Route::get('/admin/product/requests', [App\Http\Controllers\Admin\AdminProductController::class, 'productRequests']);

Route::get('/admin/products', [App\Http\Controllers\Admin\AdminProductController::class, 'products']);
Route::get('/admin/product/{id}', [App\Http\Controllers\Admin\AdminProductController::class, 'product']);
Route::get('/admin/product/{id}/{sellerId}', [App\Http\Controllers\Admin\AdminProductController::class, 'product']);
Route::get('/admin/admin-product/{id}', [App\Http\Controllers\Admin\AdminProductController::class, 'adminProduct']);
Route::get('/admin/product/{id}/{sellerId}/{type}', [App\Http\Controllers\Admin\AdminProductController::class, 'product']);
Route::get('/admin/product/{id}/{sellerId}/{type}/{lang}', [App\Http\Controllers\Admin\AdminProductController::class, 'product']);

Route::get('/admin/product-stocks', [App\Http\Controllers\Admin\AdminProductController::class, 'stocks']);
Route::post('/admin/product/stock-log', [App\Http\Controllers\Admin\AdminProductController::class, 'stockLog']);
//stock filter
Route::post('/admin/product-stocks-filter', [App\Http\Controllers\Admin\AdminProductController::class, 'stocks_filter']);

Route::post('/admin/product/requests', [App\Http\Controllers\Admin\AdminProductController::class, 'productRequests']);
Route::post('/admin/products', [App\Http\Controllers\Admin\AdminProductController::class, 'products']);
Route::post('/admin/product/validate', [App\Http\Controllers\Admin\AdminProductController::class, 'validateProduct']);
Route::post('/admin/product/save', [App\Http\Controllers\Admin\AdminProductController::class, 'saveProduct']);
Route::post('/admin/product/updateStatus', [App\Http\Controllers\Admin\AdminProductController::class, 'updateStatus']);

Route::post('/admin/product-stock/add', [App\Http\Controllers\Admin\AdminProductController::class, 'stock']);
Route::post('/admin/product-stock/save', [App\Http\Controllers\Admin\AdminProductController::class, 'saveStock']);
Route::post('admin/product-price/save', [App\Http\Controllers\Admin\AdminProductController::class, 'savePrice']);
Route::post('/admin/associativeProducts', [App\Http\Controllers\Admin\AdminProductController::class, 'associativeProducts']);

Route::get('/admin/products/offer/{id}', [App\Http\Controllers\Admin\AdminProductController::class, 'specialOffer']);
Route::post('/admin/products/offer/save', [App\Http\Controllers\Admin\AdminProductController::class, 'saveOffer']);
Route::post('/admin/delete/product/image', [App\Http\Controllers\Admin\AdminProductController::class, 'deletePrdImg']);

Route::post('/admin/products/fields', [App\Http\Controllers\Admin\AdminProductController::class, 'prdFields'])->name('fieldlist_ajax');
Route::post('/admin/products/metalprice', [App\Http\Controllers\Admin\AdminProductController::class, 'getMetalrates'])->name('fetchPrice');


//review
Route::get('/admin/product/review-content/{id}', [App\Http\Controllers\Admin\AdminProductController::class, 'review_content']);
//import
Route::post('/admin/products/import-file', [App\Http\Controllers\Admin\AdminProductController::class, 'importFile']);
Route::get('/admin/product/crop-image', [App\Http\Controllers\Admin\CropImageController::class, 'index']);
Route::post('/admin/product/crop-image', [App\Http\Controllers\Admin\CropImageController::class,'uploadCropImage'])->name('croppie.upload-image');
