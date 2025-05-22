<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('estoque', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produto_id')->constrained('produtos')->onDelete('cascade');

            $table->foreignId('variacao_id')->nullable()->constrained('variacoes')->onDelete('cascade');

            $table->integer('quantidade')->default(0);
            $table->timestamps();

            $table->unique(['produto_id', 'variacao_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estoque');
    }
};
