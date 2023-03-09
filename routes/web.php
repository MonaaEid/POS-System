<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\mainController;
use App\Http\Controllers\invoiceController;
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

// Route::get('/', function () {
//     return view('welcome');
// });
 Route::get('/home', [mainController::class, 'home']);
 Route::get('/settings', [mainController::class, 'addSeller']);
 Route::get('/addProduct', [mainController::class, 'addProduct']);
 Route::post('/store-seller', [mainController::class, 'storeSeller']);
 Route::post('/store-product', [mainController::class, 'storeProduct']);
 Route::get('/products-list', [mainController::class, 'index']);

 Route::post('/store-invoice', [invoiceController::class, 'storeInvoice']);
 Route::get('/makeInvoice', [invoiceController::class, 'makeInvoice']);
 Route::get('/invoices-list', [invoiceController::class, 'index']);
//  Route::post('/qrcode', [invoiceController::class, 'qrcode']);
 
 Route::get('/priceTwo', [invoiceController::class, 'priceTwo'])->name('priceTwo');
 Route::get('/totalPriceUpdate', [invoiceController::class, 'totalPriceUpdate'])->name('totalPriceUpdate');