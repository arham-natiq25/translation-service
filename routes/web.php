<?php

use App\Http\Controllers\SwaggerController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/api/docs', [SwaggerController::class, 'index'])->name('swagger.index');
Route::get('/api/swagger.json', [SwaggerController::class, 'json'])->name('swagger.json');
