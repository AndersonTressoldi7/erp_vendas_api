<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produto; 

class ProdutosController extends Controller
{
    public function index()
    {
        $produtos = Produto::all(); 
        return response()->json($produtos, 200, [], JSON_UNESCAPED_UNICODE);
    }

    public function buscarProdutoPeloCodigo($codigo){
        $produto = Produto::where('codigo', $codigo)->first();

        if ($produto) {
            return response()->json($produto, 200, [], JSON_UNESCAPED_UNICODE);
        } else {
            return response()->json(['message' => 'Produto não encontrado'], 404);
        }
    }

    public function salvarProduto($Produto){
       
        
    }
}
