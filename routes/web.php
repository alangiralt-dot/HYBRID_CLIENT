<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CatalogueController;
use App\Http\Controllers\OrderController;

// rutes públiques
Route::get('/', function () {
    return redirect()->route('orders.showOrderDetails.current');
});
Route::get('/comandes/current', function (\Illuminate\Http\Request $request) {
    return (new \App\Http\Controllers\OrderController())->showOrderDetails($request, 'current');
})->name('orders.showOrderDetails.current');

Route::post('/orders/add', [OrderController::class, 'addToCurrentOrder'])->name('orders.add');
Route::post('/orders/remove', [OrderController::class, 'removeFromCurrentOrder'])->name('orders.remove');

Route::get('/login', function () {
    return view('login');
})->name('login');
Route::post('/orders/clear-session', [OrderController::class, 'clearCartSession'])->name('orders.clearSession');

// rutes només accessibles amb una sessió d'usuari
Route::middleware(['auth'])->group(function () {
    Route::get('/comandes/{id}', [OrderController::class, 'showOrderDetails'])->name('orders.showOrderDetails');
    Route::post('/orders/confirm', [OrderController::class, 'confirmOrder'])->name('orders.confirm');
});

// Les rutes fixes han d'anar a dalt i la dinàmica a baix del tot.
Route::get('/{slug}', [CatalogueController::class, 'showChildProducts']);