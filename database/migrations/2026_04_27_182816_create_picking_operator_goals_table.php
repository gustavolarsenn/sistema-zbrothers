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
        Schema::create('picking_operator_goals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('picking_operator_id')->constrained('picking_operators')->cascadeOnDelete();
            $table->date('date');
            $table->unsignedInteger('goal');
            $table->time('time_goal');
            $table->timestamps();

            $table->unique(['picking_operator_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('picking_operator_goals');
    }
};
