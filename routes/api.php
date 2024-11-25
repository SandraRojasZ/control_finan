<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/api/gemini/items', function () {
    // Lógica para fazer a requisição à API Gemini e retornar os dados
});