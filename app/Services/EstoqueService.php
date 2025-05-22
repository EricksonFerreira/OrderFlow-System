<?php

namespace App\Services;

use App\Models\Estoque;
use App\Models\Produto;

class EstoqueService
{
    /**
     * Verifica a disponibilidade de estoque para um produto/variação.
     * @param int $produtoId
     * @param int $quantidade
     * @param int|null $variacaoId
     * @return bool
     */
    public function verificarDisponibilidade(int $produtoId, int $quantidade, ?int $variacaoId = null): bool
    {
        $query = Estoque::where('produto_id', $produtoId);

        if (!is_null($variacaoId)) {
            $query->where('variacao_id', $variacaoId);
        } else {
            $query->whereNull('variacao_id');
        }

        $estoqueAtual = $query->first();

        return $estoqueAtual && $estoqueAtual->quantidade >= $quantidade;
    }

    /**
     * Decrementa o estoque de um produto/variação.
     * @param int $produtoId
     * @param int $quantidade
     * @param int|null $variacaoId
     * @return bool
     * @throws \Exception Se o estoque for insuficiente.
     */
    public function decrementarEstoque(int $produtoId, int $quantidade, ?int $variacaoId = null): bool
    {
        if (!$this->verificarDisponibilidade($produtoId, $quantidade, $variacaoId)) {
            $produto = Produto::find($produtoId);
            $nomeProduto = $produto ? $produto->nome : 'Produto Desconhecido';
            throw new \Exception("Estoque insuficiente para '{$nomeProduto}'.");
        }

        $query = Estoque::where('produto_id', $produtoId);
        if (!is_null($variacaoId)) {
            $query->where('variacao_id', $variacaoId);
        } else {
            $query->whereNull('variacao_id');
        }

        return $query->decrement('quantidade', $quantidade);
    }

    /**
     * Incrementa o estoque de um produto/variação (usado em cancelamentos, devoluções, etc.).
     * @param int $produtoId
     * @param int $quantidade
     * @param int|null $variacaoId
     * @return bool
     */
    public function incrementarEstoque(int $produtoId, int $quantidade, ?int $variacaoId = null): bool
    {
        $query = Estoque::where('produto_id', $produtoId);
        if (!is_null($variacaoId)) {
            $query->where('variacao_id', $variacaoId);
        } else {
            $query->whereNull('variacao_id');
        }

        return $query->increment('quantidade', $quantidade);
    }
}