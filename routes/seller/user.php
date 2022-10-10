<?php

use Illuminate\Support\Facades\Route; 

Route::get('/seller/login', [App\Http\Controllers\Seller\Auth\LoginController::class, 'showSellerLoginForm']);
Route::get('/seller/register', [App\Http\Controllers\Seller\Auth\RegisterController::class, 'showSellerRegisterForm']);
Route::get('/seller', [App\Http\Controllers\Seller\SellerController::class, 'profile']);
Route::get('/seller/profile', [App\Http\Controllers\Seller\SellerController::class, 'profile']);
Route::view('/seller/forgot-password', 'seller.auth.forgot_pwd');


