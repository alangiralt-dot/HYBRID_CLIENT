<?php

use Illuminate\Support\Facades\Route;
/*
Route::get('/', function () {
    return view('test');
});
*/
Route::get('/', function () {
    return redirect()->route('orders.showOrderDetails.current');
});

Route::get('/comandes/current', function (\Illuminate\Http\Request $request) {
    return (new \App\Http\Controllers\OrderController())->showOrderDetails($request, 'current');
})->name('orders.showOrderDetails.current');

Route::post('/orders/add', [OrderController::class, 'addToCurrentOrder'])->name('orders.add');
Route::post('/orders/remove', [OrderController::class, 'removeFromCurrentOrder'])->name('orders.remove');
Route::get('/comandes/current', function (\Illuminate\Http\Request $request) {
    return (new \App\Http\Controllers\OrderController())->showOrderDetails($request, 'current');
})->name('orders.showOrderDetails.current');

// rutes només accessibles amb una sessió d'usuari
Route::middleware(['auth'])->group(function () {
    Route::get('/comandes/{id}', [OrderController::class, 'showOrderDetails'])->name('orders.showOrderDetails');
});
