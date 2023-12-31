<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdutosController;


Route::prefix('produtos')->group(function () {
    Route::get('/', [ProdutosController::class, 'index']);
    Route::get('/{codigo}', [ProdutosController::class, 'buscarProdutoPeloCodigo']);
});