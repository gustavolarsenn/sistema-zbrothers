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
        Schema::create('product_picking', function (Blueprint $table) {
            $table->id();
            $table->foreignId('picking_operator_id')->nullable()->constrained('picking_operators')->nullOnDelete();
            $table->string('idOrigem', 80)->nullable();
            $table->string('objOrigem', 255)->nullable();
            $table->string('situacao');
            $table->string('situacaoCheckout')->nullable();
            $table->date('dataCriacao')->nullable();
            $table->jsonb('itens')->nullable();
            $table->integer('qtdVolumes')->nullable();
            $table->string('numero', 80)->nullable();
            $table->date('dataEmissao')->nullable();
            $table->string('numeroPedidoEcommerce', 80)->nullable();
            $table->string('idFormaEnvio', 80)->nullable();
            $table->string('formaEnvio', 255)->nullable();
            $table->string('idContato', 80)->nullable();
            $table->string('destinatario', 255)->nullable();
            $table->string('situacaoOrigem', 80)->nullable();
            $table->date('dataSeparacao')->nullable();
            $table->date('dataCheckout')->nullable();
            $table->string('idOrigemVinc', 80)->nullable();
            $table->string('objOrigemVinc', 255)->nullable();
            $table->string('situacaoVenda', 80)->nullable();
            $table->index(['picking_operator_id', 'dataCriacao']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_picking');
    }
};
