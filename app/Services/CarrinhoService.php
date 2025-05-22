<?php

namespace App\Services;

use App\Models\Produto; // Usaremos o Model Produto diretamente
use App\Services\EstoqueService;
use App\Services\CupomService; // Precisaremos deste para aplicar cupom
use Illuminate\Support\Facades\Session;

class CarrinhoService
{
    protected $estoqueService;

    public function __construct(EstoqueService $estoqueService)
    {
        $this->estoqueService = $estoqueService;
    }

    /**
     * Adiciona um produto (ou variação) ao carrinho na sessão.
     * @param int $produtoId
     * @param int $quantidade
     * @param int|null $variacaoId
     * @throws \Exception Se o estoque for insuficiente ou o produto não existir.
     */
    public function adicionarProduto(int $produtoId, int $quantidade, ?int $variacaoId = null): void
    {
        $produto = Produto::find($produtoId);
        if (!$produto) {
            throw new \Exception('Produto não encontrado.');
        }

        if (!$this->estoqueService->verificarDisponibilidade($produtoId, $quantidade, $variacaoId)) {
            throw new \Exception("Estoque insuficiente para '{$produto->nome}'.");
        }

        $carrinho = Session::get('carrinho', []);
        $itemKey = $produtoId . ($variacaoId ? '_' . $variacaoId : '');

        if (isset($carrinho[$itemKey])) {
            $carrinho[$itemKey]['quantidade'] += $quantidade;
        } else {
            $carrinho[$itemKey] = [
                'produto_id' => $produto->id,
                'nome' => $produto->nome,
                'preco' => $produto->preco,
                'quantidade' => $quantidade,
                'variacao_id' => $variacaoId,
            ];
        }

        Session::put('carrinho', $carrinho);
    }

    /**
     * Remove um produto (ou variação) do carrinho.
     * @param int $produtoId
     * @param int|null $variacaoId
     */
    public function removerProduto(int $produtoId, ?int $variacaoId = null): void
    {
        $carrinho = Session::get('carrinho', []);
        $itemKey = $produtoId . ($variacaoId ? '_' . $variacaoId : '');

        if (isset($carrinho[$itemKey])) {
            unset($carrinho[$itemKey]);
            Session::put('carrinho', $carrinho);
        }
    }

    /**
     * Retorna o conteúdo atual do carrinho.
     * @return array
     */
    public function getConteudoCarrinho(): array
    {
        return Session::get('carrinho', []);
    }

    /**
     * Calcula o subtotal do carrinho (sem frete e descontos).
     * @return float
     */
    public function calcularSubtotal(): float
    {
        $subtotal = 0;
        foreach ($this->getConteudoCarrinho() as $item) {
            $subtotal += ($item['preco'] * $item['quantidade']);
        }
        return $subtotal;
    }

    /**
     * Aplica um cupom de desconto ao subtotal do carrinho.
     * @param string $codigoCupom
     * @param float $subtotal
     * @return float O valor do desconto aplicado.
     * @throws \Exception Se o cupom for inválido ou não aplicável.
     */
    public function aplicarCupom(string $codigoCupom, float $subtotal): float
    {
        $cupomService = app(CupomService::class);
        $cupom = $cupomService->validarCupom($codigoCupom, $subtotal);

        if (!$cupom) {
            throw new \Exception('Cupom inválido, expirado ou não aplicável para este subtotal.');
        }

        return $cupomService->calcularDesconto($cupom, $subtotal);
    }

    /**
     * Limpa o carrinho.
     */
    public function limparCarrinho(): void
    {
        Session::forget('carrinho');
    }
}