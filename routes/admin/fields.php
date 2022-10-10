<?php

use Illuminate\Support\Facades\Route;

Route::get('/admin/fields', [App\Http\Controllers\Admin\FieldsController::class, 'fields']);
Route::get('/admin/field/detail/{id}', [App\Http\Controllers\Admin\FieldsController::class, 'field']);
Route::get('/admin/field/detail/{id}/{type}', [App\Http\Controllers\Admin\FieldsController::class, 'field']);

Route::post('/admin/fields', [App\Http\Controllers\Admin\FieldsController::class, 'fields']);
Route::post('/admin/field/validate', [App\Http\Controllers\Admin\FieldsController::class, 'validateField']);
Route::post('/admin/field/save', [App\Http\Controllers\Admin\FieldsController::class, 'saveField']);
Route::post('/admin/field/updateStatus', [App\Http\Controllers\Admin\FieldsController::class, 'updateStatus']);
Route::post('/admin/field/deletefieldval', [App\Http\Controllers\Admin\FieldsController::class, 'deletefieldval']);
