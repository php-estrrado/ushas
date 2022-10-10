<?php

use Illuminate\Support\Facades\Route;

Route::get('/metal-rates/cron', [App\Http\Controllers\Admin\MetalratesController::class, 'fetchRates'])->name('metalrates');

