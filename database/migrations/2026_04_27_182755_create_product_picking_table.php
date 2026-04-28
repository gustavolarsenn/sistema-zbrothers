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
            $table->string('order_number', 80)->nullable();
            $table->string('product_code', 80)->nullable();
            $table->string('product_name')->nullable();
            $table->unsignedInteger('quantity')->default(1);
            $table->string('status', 40)->default('Pendente');
            $table->date('picking_date')->index();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['picking_operator_id', 'picking_date', 'status']);
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
