<?php

use App\Http\Controllers\HeladoController;
use App\Http\Controllers\PlataformaController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/helados');
Route::resource('helados', HeladoController::class)->parameters(['helados' => 'helado'])->except('show');
Route::get('/helados/{helado}/imagen', [HeladoController::class, 'imagen'])->name('helados.imagen');
Route::get('/salud', [PlataformaController::class, 'salud']);
