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
     
        $htmlDav = $this->salvarProdutosVenda($dadosVenda);

        return response()->json(['htmlDav' => $htmlDav]);
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

      return  $this->gerarDav($produtosAgrupados, $dadosVenda);
    }

    function gerarDav($produtosAgrupados, $dadosVenda) {
        // Inicializa o HTML da DAV
        $html = '<html>
                    <head>
                        <style>
                            body {
                                font-family: Arial, sans-serif;
                                text-align: center;
                                font-size: 6px; 
                            }
                            table {
                                width: 50%; /* Defina a largura desejada para a tabela */
                                margin: 20px auto; /* Centraliza a tabela */
                                border-collapse: collapse;
                            }
                            th, td {
                                border: 1px solid #ddd;
                                padding: 8px;
                                text-align: left;
                            }
                            th {
                                background-color: #f2f2f2;
                            }
                        </style>
                    </head>
                    <body>
                        <p>Dados para a Venda ' . $dadosVenda['venda_id'] . '</p>
                        <table>
                            <thead>
                                <tr>
                                    <th>ID do Produto</th>
                                    <th>Código</th>
                                    <th>Quantidade</th>
                                </tr>
                            </thead>
                            <tbody>';
        
        // Adiciona os itens à DAV
        foreach ($produtosAgrupados as $produto) {
            $html .= '<tr>
                        <td>' . $produto['produto_id'] . '</td>
                        <td>' . $produto['codigo'] . '</td>
                        <td>' . $produto['quantidade'] . '</td>
                    </tr>';
        }
    
        $html .= '</tbody>
                </table>
            </body>
        </html>';
    
        return $html;
    }
    
}
