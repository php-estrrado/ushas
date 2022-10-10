<?php

use Illuminate\Support\Facades\Route;

Route::get('/admin/attributes', [App\Http\Controllers\Admin\AttributeController::class, 'attributes']);
Route::get('/admin/attribute/detail/{id}', [App\Http\Controllers\Admin\AttributeController::class, 'attribute']);
Route::get('/admin/attribute/detail/{id}/{type}', [App\Http\Controllers\Admin\AttributeController::class, 'attribute']);

Route::post('/admin/attributes', [App\Http\Controllers\Admin\AttributeController::class, 'attributes']);
Route::post('/admin/attribute/validate', [App\Http\Controllers\Admin\AttributeController::class, 'validateAttr']);
Route::post('/admin/attribute/save', [App\Http\Controllers\Admin\AttributeController::class, 'saveAttr']);
Route::post('/admin/attribute/updateStatus', [App\Http\Controllers\Admin\AttributeController::class, 'updateStatus']);
