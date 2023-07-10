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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->date('data_nascimento');
            $table->string('cpf')->unique();
            $table->string('email')->unique();
            $table->string('senha');
            $table->timestamps();
            $table->unsignedBigInteger('endereco_cobranca_id');
            
            // Referencia da Tabela Endereco
            $table->foreign('endereco_cobranca_id')->references('id')->on('enderecos');

           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
