<?php

use Illuminate\Support\Facades\Route;

Route::get('/admin/customer/', [App\Http\Controllers\Admin\CustomerController::class, 'index'])->name('admin.customer');
Route::get('/admin/customers/{type}', [App\Http\Controllers\Admin\CustomerController::class, 'index']);
Route::post('/admin/customer/register', [App\Http\Controllers\Admin\CustomerController::class, 'register'])->name('customeregister');
Route::get('/admin/customer/view/{id}', [App\Http\Controllers\Admin\CustomerController::class, 'view_customer']);
Route::post('/admin/customer/update-profile/{id}', [App\Http\Controllers\Admin\CustomerController::class, 'update_profile']);
Route::get('/admin/customer/invoice/{id}', [App\Http\Controllers\Admin\CustomerController::class, 'invoice'])->name('customer.invoice');

Route::get('/admin/customer/wallet/', [App\Http\Controllers\Admin\CustomerWallet::class, 'index'])->name('customer.wallet');
Route::get('/admin/customer/wallet-log/{id}', [App\Http\Controllers\Admin\CustomerWallet::class, 'wallet_log']);
Route::post('/admin/customer/wallet-delete/', [App\Http\Controllers\Admin\CustomerWallet::class, 'delete_wallet']);


Route::get('/admin/customer/credits/', [App\Http\Controllers\Admin\CustomerCreditController::class, 'index'])->name('customer.credits');
Route::get('/admin/customer/credits-log/{id}', [App\Http\Controllers\Admin\CustomerCreditController::class, 'credits_log']);
Route::post('/admin/customer/credits-payment-form/', [App\Http\Controllers\Admin\CustomerCreditController::class, 'payment_form']);
Route::post('/admin/customer/credits-payment/', [App\Http\Controllers\Admin\CustomerCreditController::class, 'payment']);
Route::get('/admin/customer/credits-manage/{id}', [App\Http\Controllers\Admin\CustomerCreditController::class, 'manage_credits']);
Route::post('/admin/customer/credits-validate', [App\Http\Controllers\Admin\CustomerCreditController::class, 'validate_credits']);
Route::post('/admin/customer/credits-save', [App\Http\Controllers\Admin\CustomerCreditController::class, 'credits_save']);
Route::get('/admin/customer/credits/add', [App\Http\Controllers\Admin\CustomerCreditController::class, 'add_credits'])->name('customer.credits.add');


//customer request
Route::get('/admin/customer/request/list', [App\Http\Controllers\Admin\CustomerController::class, 'request_index'])->name('admin.customer.request');
Route::get('/admin/customer/request/view/{id}', [App\Http\Controllers\Admin\CustomerController::class, 'request_cust_view']);
Route::post('/admin/customer/request/updateStatus', [App\Http\Controllers\Admin\CustomerController::class, 'updateStatus']);


