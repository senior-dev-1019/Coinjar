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


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/setting', [App\Http\Controllers\HomeController::class, 'changeSetting'])->name('changeSetting');
Route::post('/setting', [App\Http\Controllers\HomeController::class, 'saveSetting'])->name('saveSetting');
Route::post('/getOrders', [App\Http\Controllers\HomeController::class, 'getOrders']);
Route::post('/setpair', [App\Http\Controllers\HomeController::class, 'setpair'])->name('setpair');
Route::post('/setselleraudio', [App\Http\Controllers\HomeController::class, 'setselleraudio'])->name('setselleraudio');
Route::post('/setbuyeraudio', [App\Http\Controllers\HomeController::class, 'setbuyeraudio'])->name('setbuyeraudio');
Route::post('/sellercheckstate', [App\Http\Controllers\HomeController::class, 'sellercheckstate'])->name('sellercheckstate');
Route::post('/buyercheckstate', [App\Http\Controllers\HomeController::class, 'buyercheckstate'])->name('buyercheckstate');
Route::post('/settoken', [App\Http\Controllers\HomeController::class, 'settoken'])->name('settoken');