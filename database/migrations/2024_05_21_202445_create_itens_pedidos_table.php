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
        Schema::create('itens_pedido', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained('pedidos')->onDelete('cascade');
            $table->foreignId('produto_id')->constrained('produtos')->onDelete('cascade');

            $table->foreignId('variacao_id')->nullable()->constrained('variacoes')->onDelete('set null');

            $table->integer('quantidade');
            $table->decimal('preco_unitario', 8, 2); 
            $table->string('nome_produto');

            $table->timestamps();

            $table->unique(['pedido_id', 'produto_id', 'variacao_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('itens_pedido');
    }
};