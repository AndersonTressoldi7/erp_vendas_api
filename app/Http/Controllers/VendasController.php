<?php

namespace App\Http\Controllers;

use App\Models\FormaPagamento;
use Illuminate\Http\Request;
use App\Models\Venda;
use App\Models\VendaItens;
use App\Models\produto;
use TCPDF;

class VendasController extends Controller
{
    public function salvarVenda(Request $request)
    {
        $dadosVenda = $request->all();
        $valor = $dadosVenda['valor'];
        $forma_pagamento_id = $dadosVenda['forma_pagamento'];

        $venda = Venda::create([
            'valor' => $valor,
            'forma_pagamento_id' => $forma_pagamento_id
        ]);

        $dadosVenda['venda_id'] = $venda->id;

        $htmlDav = $this->salvarProdutosVenda($dadosVenda);
       

        return response()->json(['pdf' => $htmlDav]);
    }

    private function salvarProdutosVenda($dadosVenda)
    {
        $produtosAgrupados = [];

        foreach ($dadosVenda['produtos'] as $produto) {
            $codigo = $produto['codigo'];
            $quantidade = $produto['quantidade'];

            if (isset($produtosAgrupados[$codigo])) {
                $produtosAgrupados[$codigo]['quantidade'] += $quantidade;
            } else {
                $produtosAgrupados[$codigo] = [
                    'produto_id' => $produto['id'],
                    'codigo' => $codigo,
                    'quantidade' => $quantidade
                ];
            }
        }

        foreach ($produtosAgrupados as $produto) {
            VendaItens::create([
                'produto_id' => $produto['produto_id'],
                'codigo' => $produto['codigo'],
                'quantidade' => $produto['quantidade'],
                'venda_id' => $dadosVenda['venda_id'],
            ]);
        }

        return $this->gerarDav($produtosAgrupados, $dadosVenda);
    }

    private function gerarDav($produtosAgrupados, $dadosVenda)
    {
        $pdf = new TCPDF();
        $pdf->AddPage('P', array(90, 150));

$html = '<html>
            <head>
                <style>
                body{
                    margin: 0;
                    padding: 0;
                }
                .totaisVenda{
                    font-family: Arial, sans-serif;
                    font-size: 6px;
                    
                    margin: 0;
                    padding: 0;
                }
                .centralizado {
                        font-family: Arial, sans-serif;
                        text-align: center;
                        font-size: 6px;
                    }

                    table {
                        width: 100%; 
                        margin: 10px auto; 
                        border-collapse: collapse;
                        border: none;
                    }

                    th, td {
                        border: none;
                        padding: 8px;
                        text-align: left;
                    }

                    th {
                        background-color: #f2f2f2;
                    }
                </style>
            </head>
            <body>

            <div class="centralizado"> 

            <p> Empresa teste <br>
           Endereço demonstração <br>
           nº 123 </p>

            <p>DAV - Venda nº ' . $dadosVenda['venda_id'] . '</p>
            <table>
                <thead>
                    <tr>
                        <th>ID do Produto</th>
                        <th>Produto</th>
                        <th>Código</th>
                        <th>Quantidade</th>
                    </tr>
                </thead>
                <tbody>';

            foreach ($produtosAgrupados as $produto) {

                $nomeProduto = Produto::select('nome')->find($produto['produto_id']);
                $formaPagamento = FormaPagamento::where('id', $dadosVenda['forma_pagamento'])->value('descricao');

                $html .= '<tr>
                            <td>' . $produto['produto_id'] . '</td>
                            <td>' . $nomeProduto->nome . '</td>
                            <td>' . $produto['codigo'] . '</td>
                            <td>' . $produto['quantidade'] . '</td>
                        </tr>';
            }

            $html .= '
            
            </tbody>
            </table>
            </div> 
            <div class="totaisVenda">
            <span style="float: left;">Produtos:</span> <span style="float: right;">R$'. $dadosVenda['valor'] .'</span><br>
            <span style="float: left;">Desconto:</span> <span style="float: right;">R$ 0.00</span><br>
            <span style="float: left;">Total:</span> <span style="float: right;">R$'. $dadosVenda['valor'] .'</span><br>
            <span style="float: left;">Forma de pagamento:</span> <span style="float: right;">'. $formaPagamento .'</span>
        </div>
        
            <p class="centralizado">Documento não fiscal</p>
            </body>
            </html>';

            $pdf->writeHTML($html);
            return base64_encode($pdf->Output('output.pdf', 'S'));
}

}
