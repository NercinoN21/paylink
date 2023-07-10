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
        Schema::create('contas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agencia_id');
            $table->unsignedBigInteger('user_id');
            $table->string('numero');
            $table->decimal('saldo', 10, 2);
            $table->timestamps();

            // Referencias das tabelas Agencia e Usuarios
            $table->foreign('agencia_id')->references('id')->on('agencias');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contas');
    }
};
