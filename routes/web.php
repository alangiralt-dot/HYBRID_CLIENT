<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\CatalogueController;
use App\Http\Controllers\OrderController;

// rutes públiques
Route::get('/', function () {
    return redirect()->route('orders.showOrderDetails.current');
});
Route::get('/comandes/current', function (Request $request) {
    return (new OrderController())->showOrderDetails($request, 'current');
})->name('orders.showOrderDetails.current');

Route::post('/orders/update-quantity', [OrderController::class, 'updateQuantityInCurrentOrder'])->name('orders.updateQuantity');
Route::post('/orders/remove', [OrderController::class, 'removeFromCurrentOrder'])->name('orders.remove');

Route::get('/login', function () {
    return view('login');
})->name('login');
Route::post('/orders/clear-session', [OrderController::class, 'clearCartSession'])->name('orders.clearSession');

Route::post('/orders/confirm', [OrderController::class, 'confirmOrder'])->name('orders.confirm');
Route::get('/comandes', [OrderController::class, 'showOrders'])->name('orders.showOrders');

Route::get('/comandes/{id}', [OrderController::class, 'showOrderDetails'])->name('orders.showOrderDetails');

// Les rutes fixes han d'anar a dalt i la dinàmica a baix del tot.
Route::get('/{slug}', [CatalogueController::class, 'showChildProducts']);