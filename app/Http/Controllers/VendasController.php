<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venda;
use App\Models\VendaItens;
use Dompdf\Dompdf;

class VendasController extends Controller
{
    public function salvarVenda(Request $request) {
        $dadosVenda = $request->all();
        $valor = $dadosVenda['valor'];
        $forma_pagamento_id = $dadosVenda['forma_pagamento'];
        
        $venda = Venda::create(
            [
            'valor' => $valor, 
            'forma_pagamento_id' =>   $forma_pagamento_id
            ]);

        $dadosVenda['venda_id'] = $venda->id;
        $this->salvarProdutosVenda($dadosVenda);
    }

    public function salvarProdutosVenda($dadosVenda){

        $produtosAgrupados = [];
        

        foreach($dadosVenda['produtos'] as $produto){
            $codigo = $produto['codigo'];
            $quantidade = $produto['quantidade'];


            if (isset($produtosAgrupados[$codigo])) {
                $produtosAgrupados[$codigo]['quantidade'] += $quantidade;

            }else{
                $produtosAgrupados[$codigo] = [
                    'produto_id' => $produto['id'],
                    'codigo'=> $codigo,
                    'quantidade' => $quantidade
                ];
            }
        }

        foreach($produtosAgrupados as $produto){

            VendaItens::create([
                'produto_id' => $produto['produto_id'],
                'codigo' => $produto['codigo'],
                'quantidade' => $produto['quantidade'],
                'venda_id' => $dadosVenda['venda_id'],
            ]);
        }

        $this->gerarDav($produtosAgrupados, $dadosVenda);
    }

   
    function gerarDav($produtosAgrupados, $dadosVenda) {
        // Retorno de teste em formato JSON
        return response()->json(['message' => 'Teste de retorno - Isso é apenas um teste!'], 200);
    }
    

    
    
    

    


}
