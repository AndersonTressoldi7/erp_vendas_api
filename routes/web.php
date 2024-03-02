<?php

use App\Http\Controllers\PessoasController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdutosController;
use App\Http\Controllers\VendasController;



Route::prefix('vendas')->group(function () {
    Route::get('/', [VendasController::class, 'index']);
    Route::post('/salvarVenda', [VendasController::class, 'salvarVenda']);
});

Route::prefix('pessoas')->group(function () {
    Route::get('/', [PessoasController::class, 'index']);
    Route::get('/filtrarPessoa', [PessoasController::class, 'buscarPessoaFiltrando']);
});


Route::prefix('produtos')->group(function () {
    Route::get('/filtrarProduto', [ProdutosController::class, 'buscarProdutoFiltrando']);
    Route::post('/cadastrarProduto', [ProdutosController::class, 'salvarProduto']);
    Route::put('/cadastrarProduto', [ProdutosController::class, 'editarProduto']);
    Route::get('/{codigo}', [ProdutosController::class, 'buscarProdutoPeloCodigo'])->where('codigo', '[0-9]+');
    Route::get('/', [ProdutosController::class, 'index']);
});


Route::get('/info', function () {
    return view('phpinfo');
});
