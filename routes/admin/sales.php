<?php

use Illuminate\Support\Facades\Route; 



Route::get('/admin/sales/orders', [App\Http\Controllers\Admin\SalesOrderController::class, 'orders']);
Route::get('/admin/sales/orders/{type}', [App\Http\Controllers\Admin\SalesOrderController::class, 'orders']);
Route::get('/admin/sales/order/{id}', [App\Http\Controllers\Admin\SalesOrderController::class, 'order']);
Route::get('/admin/sales/order/{id}/{type}', [App\Http\Controllers\Admin\SalesOrderController::class, 'order']);
Route::get('/admin/sales/invoice/{id}', [App\Http\Controllers\Admin\SalesOrderController::class, 'invoice']);
Route::get('/admin/sales/cancel/orders', [App\Http\Controllers\Admin\SalesOrderController::class, 'cancelOrders']);
Route::get('/admin/sales/cancel/orders/{type}', [App\Http\Controllers\Admin\SalesOrderController::class, 'cancelOrders']);
Route::get('/admin/sales/cancel/order/{id}', [App\Http\Controllers\Admin\SalesOrderController::class, 'cancelOrder']);


Route::post('/admin/sales/orders', [App\Http\Controllers\Admin\SalesOrderController::class, 'orders']);
Route::post('/admin/sales/orders/{type}', [App\Http\Controllers\Admin\SalesOrderController::class, 'orders']);
Route::post('/admin/sales/order/updateStatus', [App\Http\Controllers\Admin\SalesOrderController::class, 'updateStatus']);
Route::post('/admin/sales/order/update-status', [App\Http\Controllers\Admin\SalesOrderController::class, 'updateOrderStatus']);
Route::post('/admin/send/order/status/email', [App\Http\Controllers\Admin\SalesOrderController::class, 'orderStatusEmail']);
Route::post('/admin/sales/cancel/orders', [App\Http\Controllers\Admin\SalesOrderController::class, 'cancelOrders']);
Route::post('/admin/sales/cancel/orders/{type}', [App\Http\Controllers\Admin\SalesOrderController::class, 'cancelOrders']);

Route::get('/admin/sales/refund/order/request', [App\Http\Controllers\Admin\SalesOrderController::class, 'refundOrders']);
Route::get('/admin/sales/refund/{id}/{type}', [App\Http\Controllers\Admin\SalesOrderController::class, 'refund']);
Route::post('/admin/sales/order/refund/updateStatus', [App\Http\Controllers\Admin\SalesOrderController::class, 'refundupdateStatus']);



//Parent sales
Route::get('/admin/sales/admin-sales', [App\Http\Controllers\Admin\AdminSales::class, 'orders']);
Route::post('/admin/sales/admin-sales', [App\Http\Controllers\Admin\AdminSales::class, 'orders']);
Route::get('/admin/sales/admin-sales/order', [App\Http\Controllers\Admin\AdminSales::class, 'orders']);
Route::get('/admin/sales/admin-sales/orders/{type}', [App\Http\Controllers\Admin\AdminSales::class, 'orders']);
Route::get('/admin/sales/admin-sales/order/{id}', [App\Http\Controllers\Admin\AdminSales::class, 'order']);
Route::get('/admin/sales/admin-sales/order/{id}/{type}', [App\Http\Controllers\Admin\AdminSales::class, 'order']);


//return order
Route::get('/admin/sales/return/orders', [App\Http\Controllers\Admin\SalesOrderController::class, 'returnOrders']);
Route::get('/admin/sales/return/orders/{type}', [App\Http\Controllers\Admin\SalesOrderController::class, 'returnOrders']);
Route::get('/admin/sales/return/order/{id}', [App\Http\Controllers\Admin\SalesOrderController::class, 'returnOrder']);
Route::post('/admin/sales/order/return/updateStatus', [App\Http\Controllers\Admin\SalesOrderController::class, 'returnUpdateStatus']);
