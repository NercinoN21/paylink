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
        Schema::create('transacoes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('conta_origem_id');
            $table->unsignedBigInteger('conta_destino_id');
            $table->unsignedInteger('tipo');
            $table->decimal('valor', 10, 2);
            $table->decimal('saldo_origem_anterior', 10, 2);
            $table->decimal('saldo_origem_posterior', 10, 2);
            $table->timestamps();


            // Referencias das tabelas Agencia
            $table->foreign('conta_origem_id')->references('id')->on('contas');
            $table->foreign('conta_destino_id')->references('id')->on('contas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transacoes');
    }
};
