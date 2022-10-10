<?php

use Illuminate\Support\Facades\Route;

//Country
Route::post('/odoo/create-coupon', [App\Http\Controllers\Api\Odoo\CouponController::class, 'create']);
Route::post('/odoo/update-coupon/{coupon_id}', [App\Http\Controllers\Api\Odoo\CouponController::class, 'update']);
Route::delete('/odoo/coupon-delete/{id}', [App\Http\Controllers\Api\Odoo\CouponController::class, 'delete']);
Route::get('/odoo/coupons', [App\Http\Controllers\Api\Odoo\CouponController::class, 'list']);

Route::post('/odoo/create-category', [App\Http\Controllers\Api\Odoo\CategoryController::class, 'create']);
Route::post('/odoo/update-category/{category_id}', [App\Http\Controllers\Api\Odoo\CategoryController::class, 'update']);
Route::delete('/odoo/delete-category/{category_id}', [App\Http\Controllers\Api\Odoo\CategoryController::class, 'delete']);
Route::get('/odoo/categories', [App\Http\Controllers\Api\Odoo\CategoryController::class, 'list']);

Route::post('/odoo/create-subcategory', [App\Http\Controllers\Api\Odoo\SubCategoryController::class, 'create']);
Route::post('/odoo/update-subcategory/{subcategory_id}', [App\Http\Controllers\Api\Odoo\SubCategoryController::class, 'update']);
Route::delete('/odoo/delete-subcategory/{subcategory_id}', [App\Http\Controllers\Api\Odoo\SubCategoryController::class, 'delete']);
Route::get('/odoo/subcategories', [App\Http\Controllers\Api\Odoo\SubCategoryController::class, 'list']);

Route::post('/odoo/stock-price-create', [App\Http\Controllers\Api\Odoo\StockController::class, 'create']);
Route::post('/odoo/stock-price-update/{id}', [App\Http\Controllers\Api\Odoo\StockController::class, 'update']);
Route::delete('/odoo/delete-price/{id}', [App\Http\Controllers\Api\Odoo\StockController::class, 'delete']);
Route::get('/odoo/get-price-list/{product_id}', [App\Http\Controllers\Api\Odoo\StockController::class, 'list']);

//brand
Route::post('/odoo/brand/insert', [App\Http\Controllers\Api\Odoo\OdooBrandController::class, 'brand_creation']);
Route::post('/odoo/brand/delete', [App\Http\Controllers\Api\Odoo\OdooBrandController::class, 'brand_delete']);
Route::post('/odoo/brand/view', [App\Http\Controllers\Api\Odoo\OdooBrandController::class, 'brand_view']);
Route::get('/odoo/brand/list', [App\Http\Controllers\Api\Odoo\OdooBrandController::class, 'brand_list']);

//discount
Route::post('/odoo/discount/insert', [App\Http\Controllers\Api\Odoo\DiscountController::class, 'create_discount']);
Route::post('/odoo/discount/view', [App\Http\Controllers\Api\Odoo\DiscountController::class, 'view_discount']);
Route::get('/odoo/discount/list', [App\Http\Controllers\Api\Odoo\DiscountController::class, 'view_list']);
Route::post('/odoo/discount/delete', [App\Http\Controllers\Api\Odoo\DiscountController::class, 'delete_discount']);

//product
Route::post('/odoo/product/insert', [App\Http\Controllers\Api\Odoo\OdooProductController::class, 'product_creation']);
Route::post('/odoo/product/edit', [App\Http\Controllers\Api\Odoo\OdooProductController::class, 'product_edit']);
Route::post('/odoo/product/delete', [App\Http\Controllers\Api\Odoo\OdooProductController::class, 'product_delete']);
Route::post('/odoo/product/image/delete', [App\Http\Controllers\Api\Odoo\OdooProductController::class, 'product_image_delete']);
Route::post('/odoo/product/view', [App\Http\Controllers\Api\Odoo\OdooProductController::class, 'product_detail']);

Route::get('/odoo/country/list', [App\Http\Controllers\Api\Odoo\GeneralController::class, 'country_list']);
Route::post('/odoo/state/list', [App\Http\Controllers\Api\Odoo\GeneralController::class, 'state_list']);
Route::post('/odoo/city/list', [App\Http\Controllers\Api\Odoo\GeneralController::class, 'city_list']);

//Tax
Route::post('/odoo/tax/list', [App\Http\Controllers\Api\Odoo\GeneralController::class, 'tax_list']);
Route::post('/odoo/tax/view', [App\Http\Controllers\Api\Odoo\GeneralController::class, 'tax_view']);
Route::post('/odoo/tax/add', [App\Http\Controllers\Api\Odoo\GeneralController::class, 'tax_creation']);
Route::post('/odoo/tax/edit', [App\Http\Controllers\Api\Odoo\GeneralController::class, 'tax_edit']);
Route::post('/odoo/tax/delete', [App\Http\Controllers\Api\Odoo\GeneralController::class, 'tax_delete']);

Route::post('/odoo/sales/insert', [App\Http\Controllers\Api\Odoo\SalesController::class, 'placeorder']);
Route::get('/odoo/sales/list', [App\Http\Controllers\Api\Odoo\SalesController::class, 'list']);
Route::post('/odoo/sales/{sale_id}', [App\Http\Controllers\Api\Odoo\SalesController::class, 'update']);
Route::post('/odoo/sales/cancel/{sale_id}', [App\Http\Controllers\Api\Odoo\SalesController::class, 'cancelOrder']);
Route::post('/odoo/sales/return/{id}', [App\Http\Controllers\Api\Odoo\SalesController::class, 'retunOrder']);
Route::get('/odoo/sales/returns/list', [App\Http\Controllers\Api\Odoo\SalesController::class, 'retunOrderList']);

Route::post('/odoo/add-stock', [App\Http\Controllers\Api\Odoo\StockController::class, 'stockcreate']);
Route::post('/odoo/update-stock/{id}', [App\Http\Controllers\Api\Odoo\StockController::class, 'stockupdate']);
Route::delete('/odoo/delete-stock/{id}', [App\Http\Controllers\Api\Odoo\StockController::class, 'stockdelete']);
Route::get('/odoo/get-stock-list/{product_id}', [App\Http\Controllers\Api\Odoo\StockController::class, 'stocklist']);

