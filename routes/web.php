<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseOrderController;
use App\Models\PurchaseOrder;
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
    return redirect()->route('dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::resource('product', ProductController::class)->middleware(['auth', 'verified']);
Route::resource('customer', CustomerController::class)->middleware(['auth', 'verified']);
Route::resource('order', PurchaseOrderController::class)->middleware(['auth', 'verified']);

Route::post('/product/search', [ProductController::class, 'search'])->name('product.search');
Route::post('/customer/search', [CustomerController::class, 'search'])->name('customer.search');
Route::post('/order/search', [PurchaseOrderController::class, 'search'])->name('order.search');
Route::group(['prefix'=>'/order'], function (){
    Route::get('/print-surat-jalan/{order}', [PurchaseOrderController::class, 'printSuratJalan'])->name('printSuratjalan');
    Route::get('/print-invoice/{order}', [PurchaseOrderController::class, 'printInvoice'])->name('printInvoice');
    Route::get('/surat-jalan/{order}', [PurchaseOrderController::class, 'showSuratJalan'])->name('order.Suratjalan');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
