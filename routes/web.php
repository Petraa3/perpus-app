<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\ChatController;

Route::get('/buku', [BukuController::class, 'index']);

Route::get('/create', [BukuController::class, 'create']);
Route::post('/buku', [BukuController::class, 'store']);

Route::get('/chat', [ChatController::class, 'index']);
Route::post('/chat', [ChatController::class, 'send']);