<?php

use App\Http\Controllers\PickingOperatorController;
use App\Http\Controllers\PickingOperatorGoalController;
use App\Http\Controllers\ProductPickingController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PickingOperatorGoalController::class, 'index'])->name('dashboard');

Route::resource('picking-operators', PickingOperatorController::class);
Route::resource('product-picking', ProductPickingController::class);
Route::resource('picking-operator-goals', PickingOperatorGoalController::class);
