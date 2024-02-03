<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('formas_pagamento', function (Blueprint $table) {
            $table->id();
            $table->string("descricao");
            $table->timestamps();
        });

        $formasPagamento = [ 
            ['descricao' => 'Dinheiro'],
            ['descricao' => 'Cartão'],
            ['descricao' => 'Vale alimentação'],
            ['descricao' => 'Vale presente'],
            ['descricao' => 'Cheque'],
            ['descricao' => 'Boleto'],
            ['descricao' => 'Pix'],
            ['descricao' => 'Depósito Bancário'],
            ['descricao' => 'Crediário']
        ];

        DB::table('formas_pagamento')->insert($formasPagamento);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_formas_pagamento');
    }
};
