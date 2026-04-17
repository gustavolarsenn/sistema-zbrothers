<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('purchase_order_details', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('numero');
            $table->string('numero_ecommerce')->nullable();
            $table->date('data_pedido')->nullable();
            $table->date('data_prevista')->nullable();
            $table->date('data_faturamento')->nullable();
            $table->date('data_envio')->nullable();
            $table->date('data_entrega')->nullable();
            $table->string('id_lista_preco')->nullable();
            $table->string('descricao_list_preco')->nullable();
            $table->jsonb('cliente');
            $table->jsonb('itens');
            $table->jsonb('parcelas');
            $table->jsonb('marcadores');
            $table->string('condicao_pagamnto')->nullable();
            $table->string('forma_pagamento')->nullable();
            $table->string('meio_pagamento')->nullable();
            $table->string('nome_transportador')->nullable();
            $table->string('frete_por_conta')->nullable();
            $table->decimal('valor_frete', 10, 2);
            $table->decimal('valor_desconto', 10, 2);
            $table->decimal('total_produtos', 10, 2);
            $table->decimal('total_pedido', 10, 2);
            $table->string('numero_ordem_compra')->nullable();
            $table->string('deposito');
            $table->string('forma_envio');
            $table->string('situacao');
            $table->string('obs')->nullable();
            $table->string('obs_interna')->nullable();
            $table->string('id_vendedor');
            $table->string('codigo_rastreamento')->nullable();
            $table->string('url_rastreamento')->nullable();
            $table->string('id_nota_fiscal');
            $table->jsonb('pagamentos_integrados');
            $table->string('id_natureza_operacao')->nullable();
            $table->jsonb('ecommerce')->nullable();
            $table->jsonb('endereco_entrega')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_order_details');
    }
};
