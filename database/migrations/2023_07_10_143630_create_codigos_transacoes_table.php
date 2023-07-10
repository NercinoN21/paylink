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
        Schema::create('codigos_transacoes', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('tipo');
            $table->string('codigo');
            $table->string('numero_conta_origem');
            $table->dateTime('data_expiracao');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('codigos_transacoes');
    }
};
