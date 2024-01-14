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

    public function salvarProduto(Request $request){
        $dadosProduto = $request->input('produto');

        if(Produto::where('codigo', $dadosProduto['codigo'])->first()){
            return response()->json(['message' => 'Já existe um produto com esse código'], 409);
        }else{
            Produto::create($dadosProduto);
        return response()->json(['message' => 'Produto salvo com sucesso!'], 201);
        }
        
    }

    public function buscarProdutoFiltrando(Request $request){

        $tipoFiltro = $request->input('checkboxTipoFiltro');
        $filtro = $request->input('filtros');

        if($tipoFiltro=="descricao"){
            $produtos = Produto::where('nome', 'like', "%$filtro%")->get();
        }else{
            $produtos = Produto::where('codigo', 'like', "%$filtro%")->get();
        }
        
        return response()->json($produtos);
    }
    
}
