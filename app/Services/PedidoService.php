<?php

namespace App\Services;

use App\Models\Pedido;      // Usaremos o Model Pedido diretamente
use App\Models\ItemPedido; // Usaremos o Model ItemPedido diretamente
use App\Models\Produto;    // Pode ser necessário para buscar dados de produto
use App\Services\EstoqueService;
use App\Services\EmailService; // Para enviar e-mail de confirmação
use Illuminate\Support\Facades\DB; // Para transações de banco de dados

class PedidoService
{
    protected $estoqueService;
    protected $emailService;

    public function __construct(EstoqueService $estoqueService, EmailService $emailService)
    {
        $this->estoqueService = $estoqueService;
        $this->emailService = $emailService;
    }

    /**
     * Cria um novo pedido no banco de dados e gerencia o estoque.
     * @param array $itensCarrinho Conteúdo do carrinho de compras.
     * @param array $dadosCliente Dados do cliente (nome, email, endereço, etc.).
     * @param float $valorFrete
     * @param float $descontoCupom
     * @param float $totalPedido
     * @return Pedido O objeto Pedido criado.
     * @throws \Exception Se o estoque for insuficiente ou ocorrer outro erro.
     */
    public function criarPedido(array $itensCarrinho, array $dadosCliente, float $valorFrete, float $descontoCupom, float $totalPedido): Pedido
    {
        DB::beginTransaction();

        try {
            // Cria o pedido principal
            $pedido = Pedido::create([
                'cliente_nome' => $dadosCliente['nome_cliente'],
                'cliente_email' => $dadosCliente['email_cliente'],
                'endereco_cep' => $dadosCliente['cep'],
                'endereco_rua' => $dadosCliente['endereco'],
                'endereco_numero' => $dadosCliente['numero'],
                'endereco_bairro' => $dadosCliente['bairro'],
                'endereco_cidade' => $dadosCliente['cidade'],
                'endereco_estado' => $dadosCliente['estado'],
                'valor_subtotal' => $this->calcularSubtotalItens($itensCarrinho),
                'valor_frete' => $valorFrete,
                'valor_desconto' => $descontoCupom,
                'valor_total' => $totalPedido,
                'status' => 'pendente',
            ]);

            // Adiciona os itens do pedido e decrementa o estoque
            foreach ($itensCarrinho as $item) {
                // Diminui o estoque do produto
                $this->estoqueService->decrementarEstoque(
                    $item['produto_id'],
                    $item['quantidade'],
                    $item['variacao_id']
                );

                // Cria o item do pedido
                ItemPedido::create([
                    'pedido_id' => $pedido->id,
                    'produto_id' => $item['produto_id'],
                    'variacao_id' => $item['variacao_id'],
                    'quantidade' => $item['quantidade'],
                    'preco_unitario' => $item['preco'],
                    'nome_produto' => $item['nome'],
                ]);
            }

            DB::commit();
            return $pedido;

        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Erro ao criar pedido e gerenciar estoque: ' . $e->getMessage());
        }
    }

    /**
     * Busca um pedido pelo ID.
     * @param int $id
     * @return Pedido|null
     */
    public function buscarPedidoPorId(int $id)
    {
        return Pedido::with('itens')->find($id);
    }

    /**
     * Processa a atualização de status de um pedido via webhook.
     * @param int $pedidoId
     * @param string $novoStatus
     * @return bool
     * @throws \Exception Se o pedido não for encontrado ou o status for inválido.
     */
    public function processarWebhook(int $pedidoId, string $novoStatus): bool
    {
        DB::beginTransaction();
        try {
            $pedido = Pedido::find($pedidoId);

            if (!$pedido) {
                throw new \Exception('Pedido não encontrado para o webhook.');
            }

            if ($novoStatus === 'cancelado') {
                $itensPedido = $pedido->itens;
                foreach ($itensPedido as $item) {
                    $this->estoqueService->incrementarEstoque(
                        $item->produto_id,
                        $item->quantidade,
                        $item->variacao_id
                    );
                }
                $pedido->delete();
            } else {
                $pedido->status = $novoStatus;
                $pedido->save();
            }

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Erro ao processar webhook do pedido: ' . $e->getMessage());
        }
    }

    /**
     * Recalcula o subtotal dos itens do carrinho.
     * Usado internamente para segurança na criação do pedido.
     * @param array $itensCarrinho
     * @return float
     */
    private function calcularSubtotalItens(array $itensCarrinho): float
    {
        $subtotal = 0;
        foreach ($itensCarrinho as $item) {
            $subtotal += ($item['preco'] * $item['quantidade']);
        }
        return $subtotal;
    }
}