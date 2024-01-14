<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdutosController;


Route::prefix('produtos')->group(function () {
    Route::get('/filtrarProduto', [ProdutosController::class, 'buscarProdutoFiltrando']);
    Route::post('/cadastrarProduto', [ProdutosController::class, 'salvarProduto']);
    Route::get('/{codigo}', [ProdutosController::class, 'buscarProdutoPeloCodigo'])->where('codigo', '[0-9]+');
    Route::get('/', [ProdutosController::class, 'index']);
});


Route::get('/info', function () {
    return view('phpinfo');
});
