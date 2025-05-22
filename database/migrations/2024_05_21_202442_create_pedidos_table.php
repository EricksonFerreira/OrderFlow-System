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
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->string('cliente_nome');
            $table->string('cliente_email');
            $table->string('endereco_cep', 9);
            $table->string('endereco_rua');
            $table->string('endereco_numero', 20);
            $table->string('endereco_bairro');
            $table->string('endereco_cidade');
            $table->string('endereco_estado', 2);

            $table->decimal('valor_subtotal', 8, 2);
            $table->decimal('valor_frete', 8, 2)->default(0.00);
            $table->decimal('valor_desconto', 8, 2)->default(0.00);
            $table->decimal('valor_total', 8, 2);

            $table->string('status')->default('pendente');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};