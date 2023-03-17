<?php

use Illuminate\Support\Facades\Route;

//Salesmanagement

Route::post('/crm/Salesmanagement/insert', [App\Http\Controllers\Api\SalesmanagementController::class, 'insert']);
Route::post('/crm/Salesmanagement/update', [App\Http\Controllers\Api\SalesmanagementController::class, 'update']);
Route::post('/crm/Salesmanagement/delete', [App\Http\Controllers\Api\SalesmanagementController::class, 'delete']);
Route::post('/crm/Salesmanagement/list', [App\Http\Controllers\Api\SalesmanagementController::class, 'list']);

//Order Status
Route::post('/crm/Salesmanagement/orderstatus', [App\Http\Controllers\Api\SalesmanagementController::class, 'orderstatus']);