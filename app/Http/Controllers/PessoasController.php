<?php

namespace App\Http\Controllers;
use App\Models\Pessoa;
use Illuminate\Http\Request;

class PessoasController extends Controller
{
    public function index()
    {
        $pessoas = Pessoa::all(); 
        return response()->json($pessoas, 200, [], JSON_UNESCAPED_UNICODE);
    }


    public function buscarPessoaFiltrando(Request $request){

        $tipoFiltro = $request->input('checkboxTipoFiltro');
        $filtro = $request->input('filtros');

       
            $pessoas = Pessoa::where('nome', 'like', "%$filtro%")->get();
       
        
        return response()->json($pessoas);
    }

}
