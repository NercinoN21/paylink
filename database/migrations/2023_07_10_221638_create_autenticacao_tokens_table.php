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
        Schema::create('autenticacao_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('token');
            $table->dateTime('data_expiracao');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            // Referencia da tabela users
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('autenticacao_tokens');
    }
};
