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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('numero', 50);
            $table->string('numero_ecommerce', 50)->nullable();
            $table->date('data_pedido');
            $table->date('data_prevista')->nullable();
            $table->string('nome', 255);
            $table->decimal('valor', 10, 2);
            $table->string('id_vendedor', 50)->nullable();
            $table->string('nome_vendedor', 255)->nullable();
            $table->string('situacao', 50);
            $table->string('codigo_rastreamento', 255)->nullable();
            $table->string('url_rastreamento', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
