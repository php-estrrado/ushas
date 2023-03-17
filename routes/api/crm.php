<?php

use Illuminate\Support\Facades\Route;

//Country
Route::post('/crm/customer/insert', [App\Http\Controllers\Api\CRM\CrmCustomerController::class, 'insert_customer']);
Route::post('/crm/customer/sales', [App\Http\Controllers\Api\CRM\CrmCustomerSales::class, 'customer_sales']);
Route::post('/crm/customer/all-sales-crm', [App\Http\Controllers\Api\CRM\CrmCustomerSales::class, 'customer_all_sales_crm']);
Route::post('/crm/customer/all-sales', [App\Http\Controllers\Api\CRM\CrmCustomerSales::class, 'customer_all_sales']);
Route::post('/crm/customer/coupon/list', [App\Http\Controllers\Api\CRM\CrmCustomerController::class, 'offerlist']);

Route::post('/crm/add-bussiness-category', [App\Http\Controllers\Api\CRM\CrmCategoryController::class, 'add']);
Route::post('/crm/update-bussiness-category', [App\Http\Controllers\Api\CRM\CrmCategoryController::class, 'update']);
Route::post('/crm/delete-bussiness-category', [App\Http\Controllers\Api\CRM\CrmCategoryController::class, 'deleteCategory']);

//crm and odoo
Route::post('/crm/customer/update', [App\Http\Controllers\Api\CRM\CrmCustomerController::class, 'update_customer']);

//odoo
// Route::post('/odoo/customer/delete', [App\Http\Controllers\Api\CRM\CrmCustomerController::class, 'odoo_delete_customer']);
Route::post('/odoo/customer/delete', [App\Http\Controllers\Api\CRM\CrmCustomerController::class, 'odoo_delete_customer']);
Route::post('/crm/customer/list', [App\Http\Controllers\Api\CRM\CrmCustomerController::class, 'customer_list']);

//Delivery
Route::post('/crm/delivery/insert', [App\Http\Controllers\Api\DeliveryController::class, 'insert']);
Route::post('/crm/delivery/update', [App\Http\Controllers\Api\DeliveryController::class, 'update']);
Route::post('/crm/delivery/delete', [App\Http\Controllers\Api\DeliveryController::class, 'delete']);
Route::post('/crm/delivery/list', [App\Http\Controllers\Api\DeliveryController::class, 'list']);

//Coupon
Route::post('/crm/coupon/insert', [App\Http\Controllers\Api\CouponController::class, 'insert']);
Route::post('/crm/coupon/update', [App\Http\Controllers\Api\CouponController::class, 'update']);
Route::post('/crm/coupon/delete', [App\Http\Controllers\Api\CouponController::class, 'delete']);
Route::post('/crm/coupon/list', [App\Http\Controllers\Api\CouponController::class, 'list']);

//Discount
Route::post('/crm/discount/insert', [App\Http\Controllers\Api\DiscountController::class, 'insert']);
Route::post('/crm/discount/update', [App\Http\Controllers\Api\DiscountController::class, 'update']);
Route::post('/crm/discount/delete', [App\Http\Controllers\Api\DiscountController::class, 'delete']);
Route::post('/crm/discount/list', [App\Http\Controllers\Api\DiscountController::class, 'list']);
