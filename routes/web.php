<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('/welcome');
});

Auth::routes();

Route::get('/', [App\Http\Controllers\Admin\AdminController::class, 'index']);

 Route::view('/home', 'home')->middleware('auth');
 
Route::get('/testmail', [App\Http\Controllers\Admin\AdminController::class, 'sendmail']);
Route::get('/credits-cron', [App\Http\Controllers\CreditCronController::class, 'validateCredit']);
Route::get('/credits-days-cron', [App\Http\Controllers\CreditCronController::class, 'validateCreditDays']);
foreach (glob(__DIR__ . '/admin/*.php') as $filename) { require_once($filename); }
foreach (glob(__DIR__ . '/seller/*.php') as $filename) { require_once($filename); }


Route::post('/admin/getDropdown', [App\Http\Controllers\Admin\HomeController::class, 'dropdownData']);

Route::get('/lockscreen', [App\Http\Controllers\HomeController::class, 'lockscreen'])->name('admin.lock');
Route::get('/site-config', [App\Http\Controllers\HomeController::class, 'configSet'])->name('admin.config');
Route::post('/admin/save-config', [App\Http\Controllers\HomeController::class, 'saveSet']);
Route::get('/admin/clear-cache', [App\Http\Controllers\HomeController::class, 'clearSettings']);
Route::post('/admin/unlock-password', [App\Http\Controllers\HomeController::class, 'unlockpwd']);

Route::stripeWebhooks('stripe-webhook');
