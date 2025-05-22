<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CarrinhoService;
use App\Services\PedidoService;
use App\Services\ViaCepService;
use App\Services\FreteService; // Para calcular o frete
use App\Services\EmailService; // Para enviar e-mail de confirmação
use App\Http\Requests\CepConsultaRequest;
use App\Http\Requests\CarrinhoRemoveItemRequest;
use App\Http\Requests\PedidoFinalizarRequest;
use App\Http\Requests\PedidoWebhookRequest;
use App\Http\Requests\CupomValidoRequest;
use App\Services\CupomService;

class PedidoController extends Controller
{
    protected $carrinhoService;
    protected $pedidoService;
    protected $viaCepService;
    protected $freteService;
    protected $emailService;
    protected $cupomService;

    public function __construct(
        CarrinhoService $carrinhoService,
        PedidoService $pedidoService,
        ViaCepService $viaCepService,
        FreteService $freteService,
        EmailService $emailService,
        CupomService $cupomService
    ) {
        $this->carrinhoService = $carrinhoService;
        $this->pedidoService = $pedidoService;
        $this->viaCepService = $viaCepService;
        $this->freteService = $freteService;
        $this->emailService = $emailService;
        $this->cupomService = $cupomService;
    }

    /**
     * Exibe o conteúdo atual do carrinho de compras.
     * GET /carrinho
     */
    public function verCarrinho()
    {
        $itensCarrinho = $this->carrinhoService->getConteudoCarrinho();
        $subtotal = $this->carrinhoService->calcularSubtotal(); // Método a ser criado no CarrinhoService
        $valorFrete = $this->freteService->calcularFrete($subtotal);

        // Retorna a view do carrinho, passando os itens, subtotal e frete
        return view('carrinho.index', compact('itensCarrinho', 'subtotal', 'valorFrete'));
    }

    /**
     * Remove um item do carrinho.
     * POST /carrinho/remover
     */
    public function removerDoCarrinho(CarrinhoRemoveItemRequest $request)
    {
        $validated = $request->validated();

        $produtoId = $validated['produto_id'];
        $variacaoId = $validated['variacao_id'] ?? null;

        $this->carrinhoService->removerProduto($produtoId, $variacaoId);

        return back()->with('success', 'Produto removido do carrinho.');
    }

    /**
     * Consulta o CEP via ViaCEP API.
     * POST /cep/consultar (usado via AJAX na view)
     */
    public function consultarCep(CepConsultaRequest $request)
    {
        $validated = $request->validated();

        try {
            $endereco = $this->viaCepService->consultarCep($validated['cep']);
            return response()->json($endereco);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }


    /**
     * Valida um cupom de desconto.
     * POST /pedido/cupom-valido (usado via AJAX na view)
     */
    public function cupomValido(CupomValidoRequest $request)
    {
        $validated = $request->validated();

        try {
            $cupom = $this->cupomService->validarCupom($validated['cupom_codigo'], $validated['subtotal']);
            
            return response()->json(['valido' => true, 'desconto' => $cupom->desconto,'tipo' => $cupom->tipo_desconto]);
        } catch (\Exception $e) {
            return response()->json(['valido' => false, 'message' => $e->getMessage()], 400);
        }
    }

    /**
     * Processa a finalização de um pedido.
     * POST /pedido/finalizar
     */
    public function finalizarPedido(PedidoFinalizarRequest $request)
    {
        // Validação dos dados do cliente e endereço
        $validated = $request->validated();

        try {
            $itensCarrinho = $this->carrinhoService->getConteudoCarrinho();
            if (empty($itensCarrinho)) {
                throw new \Exception('Seu carrinho está vazio.');
            }

            $subtotal = $this->carrinhoService->calcularSubtotal();
            $valorFrete = $this->freteService->calcularFrete($subtotal);
            $descontoCupom = 0;

            // Se houver cupom, tenta aplicá-lo
            if ($request->filled('cupom_codigo')) {
                try {
                    $descontoCupom = $this->carrinhoService->aplicarCupom($request->input('cupom_codigo'), $subtotal);
                    if ($descontoCupom === 0) {
                        return back()->withInput()->withErrors(['cupom' => 'Cupom inválido ou não aplicável.']);
                    }
                } catch (\Exception $e) {
                     return back()->withInput()->withErrors(['cupom' => $e->getMessage()]);
                }
            }

            $totalPedido = ($subtotal - $descontoCupom) + $valorFrete;

            // Dados do cliente e endereço
            $dadosCliente = $validated;

            // Cria o pedido no banco de dados e gerencia o estoque
            $pedido = $this->pedidoService->criarPedido($itensCarrinho, $dadosCliente, $valorFrete, $descontoCupom, $totalPedido);

            // Limpa o carrinho após a finalização do pedido
            $this->carrinhoService->limparCarrinho();

            // Envia o e-mail de confirmação (assíncrono seria ideal)
            $this->emailService->enviarEmailConfirmacao($pedido, $dadosCliente['email_cliente']);

            return redirect()->route('pedido.confirmacao', $pedido->id)->with('success', 'Pedido finalizado com sucesso!');

        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Erro ao finalizar pedido: ' . $e->getMessage()]);
        }
    }

    /**
     * Exibe a página de confirmação do pedido.
     * GET /pedido/confirmacao/{id}
     */
    public function confirmacao($id)
    {
        $pedido = $this->pedidoService->buscarPedidoPorId($id);

        if (!$pedido) {
            return redirect()->route('home')->with('error', 'Pedido não encontrado.');
        }

        return view('pedido.confirmacao', compact('pedido'));
    }

    /**
     * Endpoint do webhook para receber atualizações de status de pedidos.
     * POST /webhook/pedido
     */
    public function webhook(PedidoWebhookRequest $request)
    {
        // Validar que a requisição é um POST e contém os dados esperados
        $validated = $request->validated();

        $pedidoId = $validated['id'];
        $novoStatus = $validated['status'];

        try {
            $this->pedidoService->processarWebhook($pedidoId, $novoStatus);
            // Retorna um status 200 OK para o serviço que chamou o webhook
            return response()->json(['message' => 'Webhook processado com sucesso.'], 200);
        } catch (\Exception $e) {
            // Em caso de erro, retorna um status de erro apropriado
            return response()->json(['error' => 'Erro ao processar webhook: ' . $e->getMessage()], 500);
        }
    }
}