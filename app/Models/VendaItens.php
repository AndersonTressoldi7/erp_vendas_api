<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendaItens extends Model
{
    use HasFactory;

    protected $table = 'vendas_itens';

    protected $fillable = [
        'venda_id', 'produto_id', 'quantidade'
    ];
}
