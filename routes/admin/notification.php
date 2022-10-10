<?php

use Illuminate\Support\Facades\Route;

Route::get('/admin/notifications', [App\Http\Controllers\Admin\NotificationController::class, 'list'])->name('notifications.list');

