<?php

use App\Http\Controllers\ItemController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ItemController::class, 'index']);
Route::post('/items', [ItemController::class, 'store']);
Route::delete('/items/{item}', [ItemController::class, 'destroy']);
Route::get('/export', [ItemController::class, 'export'])->name('export');
