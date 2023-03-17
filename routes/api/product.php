<?php

use Illuminate\Support\Facades\Route;

//Parent products

Route::post('/crm/product/insert', [App\Http\Controllers\Api\Products\ProductController::class, 'insert_product']);
Route::post('/crm/product/update', [App\Http\Controllers\Api\Products\ProductController::class, 'update_product']);

//Child Parent products
Route::post('/crm/product/insert_child', [App\Http\Controllers\Api\Products\ProductController::class, 'insert_child']);
Route::post('/crm/product/update_child', [App\Http\Controllers\Api\Products\ProductController::class, 'update_child']);

//Product image
Route::post('/crm/product/insert_image', [App\Http\Controllers\Api\Products\ProductController::class, 'insert_image']);

//Cartons
Route::post('/crm/product/add_product_assortment', [App\Http\Controllers\Api\Products\ProductController::class, 'add_product_assortment']);
Route::post('/crm/product/update_product_assortment', [App\Http\Controllers\Api\Products\ProductController::class, 'update_product_assortment']);

//Insert Product Assortment
Route::post('/crm/product/insert_part_assortment', [App\Http\Controllers\Api\Products\ProductController::class, 'insert_part_assortment']);

Route::post('/crm/product/update_product_assortment_master', [App\Http\Controllers\Api\Products\ProductController::class, 'update_product_assortment_master']);
Route::post('/crm/product/delete_product_assortment_details', [App\Http\Controllers\Api\Products\ProductController::class, 'delete_product_assortment_details']);
Route::post('/crm/product/insert_product_assortment_details', [App\Http\Controllers\Api\Products\ProductController::class, 'insert_product_assortment_details']);

//Assortments
Route::post('/crm/product/insert_assortment', [App\Http\Controllers\Api\Products\ProductController::class, 'insert_assortment']);
Route::post('/crm/product/update_assortment', [App\Http\Controllers\Api\Products\ProductController::class, 'update_assortment']);
Route::post('/crm/product/delete_assortment', [App\Http\Controllers\Api\Products\ProductController::class, 'delete_assortment']);
Route::post('/crm/product/assortment_list', [App\Http\Controllers\Api\Products\ProductController::class, 'assortment_list']);

//Stocks
Route::post('/crm/product/update_stock', [App\Http\Controllers\Api\Products\ProductController::class, 'update_stock']);


//category
Route::post('/crm/product/insert_category', [App\Http\Controllers\Api\Products\ProductController::class, 'insert_category']);
Route::post('/crm/product/update_category', [App\Http\Controllers\Api\Products\ProductController::class, 'update_category']);
Route::post('/crm/product/delete_category', [App\Http\Controllers\Api\Products\ProductController::class, 'delete_category']);
Route::post('/crm/product/category_list', [App\Http\Controllers\Api\Products\ProductController::class, 'category_list']);