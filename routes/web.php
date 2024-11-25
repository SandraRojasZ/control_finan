<?php

use App\Http\Controllers\ChatGeminiController;
use App\Http\Controllers\ResultadosController;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\CriaItem;
use App\Http\Livewire\ItemsLista;

Route::get('/', function () {
    return view('welcome');
});
/*Sanctum é ideal para aplicativos SPA (Single Page Applications) e API */
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/items', function () {
        return view('items');
    })->name('items');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/resultados', [ResultadosController::class, 'resultados'])->name('resultados');
});

//use App\Livewire\FinancialConsultant;

//Route::get('/financial-consultant', FinancialConsultant::class);


