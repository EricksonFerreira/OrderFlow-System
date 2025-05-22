<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ProdutoService; // Vamos criar este serviço depois
use App\Services\EstoqueService; // E este também
use App\Services\CarrinhoService; // E este!
use App\Http\Requests\ProdutoStoreRequest;
use App\Http\Requests\ProdutoUpdateRequest;
use App\Http\Requests\CarrinhoAddItemRequest;
use App\Http\Requests\CarrinhoRemoveItemRequest;


class ProdutoController extends Controller
{
    protected $produtoService;
    protected $estoqueService; // Será usado para o estoque em conjunto com o ProdutoService
    protected $carrinhoService;

    // O construtor é onde injetamos as dependências (os serviços)
    // Isso é chamado de Injeção de Dependência e é uma boa prática
    public function __construct(
        ProdutoService $produtoService,
        EstoqueService $estoqueService,
        CarrinhoService $carrinhoService
    ) {
        $this->produtoService = $produtoService;
        $this->estoqueService = $estoqueService;
        $this->carrinhoService = $carrinhoService;
    }

    /**
     * Exibe a lista de todos os produtos.
     * GET /produtos
     */
    public function index()
    {
        // Chama o serviço para obter todos os produtos
        $produtos = $this->produtoService->listarTodosProdutos();
        // Retorna a view 'produtos.index' passando os produtos para ela
        return view('produtos.index', compact('produtos'));
    }

    /**
     * Mostra o formulário para criar um novo produto.
     * GET /produtos/criar
     */
    public function create()
    {
        // Retorna a view com o formulário de criação
        return view('produtos.create');
    }

    /**
     * Armazena um novo produto no banco de dados.
     * POST /produtos
     */
    public function store(ProdutoStoreRequest $request)
    {
        $validated = $request->validated();
        try {
            // Chama o serviço para cadastrar o produto e gerenciar o estoque inicial
            // Passamos os dados do produto e a quantidade de estoque inicial
            $produto = $this->produtoService->cadastrarProduto(
                [
                    'nome' => $validated['nome'], // Dados do produto
                    'preco' => $validated['preco'], // Preço do produto
                    // 'descricao' => $validated['descricao'], // Descrição do produto
                ],
                $validated['estoque'] // Estoque inicial
                // $request->input('variacoes') // Se houver variações
            );

            // Redireciona para a página de detalhes do produto ou para a lista com mensagem de sucesso
            return redirect()->route('produtos.index')->with('success', 'Produto cadastrado com sucesso!');
        } catch (\Exception $e) {
            // Em caso de erro, redireciona de volta com os inputs e uma mensagem de erro
            return back()->withInput()->withErrors(['error' => 'Erro ao cadastrar produto: ' . $e->getMessage()]);
        }
    }

    /**
     * Exibe os detalhes de um produto específico.
     * GET /produtos/{id}
     */
    public function show($id)
    {
        // Busca o produto pelo ID através do serviço
        $produto = $this->produtoService->buscarProdutoPorId($id);

        if (!$produto) {
            // Se o produto não for encontrado, redireciona ou mostra um erro 404
            return redirect()->route('produtos.index')->with('error', 'Produto não encontrado.');
        }

        // Retorna a view 'produtos.show' com os detalhes do produto
        return view('produtos.show', compact('produto'));
    }

    /**
     * Mostra o formulário para editar um produto existente.
     * GET /produtos/{id}/editar
     */
    public function edit($id)
    {
        // Busca o produto pelo ID para preencher o formulário
        $produto = $this->produtoService->buscarProdutoPorId($id);

        if (!$produto) {
            return redirect()->route('produtos.index')->with('error', 'Produto não encontrado para edição.');
        }

        // Retorna a view 'produtos.edit' com os dados do produto
        return view('produtos.edit', compact('produto'));
    }

    /**
     * Atualiza um produto existente no banco de dados.
     * PUT/PATCH /produtos/{id}
     */
    public function update(ProdutoUpdateRequest $request, $id)
    {
        $validated = $request->validated();
        try {
            // Chama o serviço para atualizar o produto e seu estoque
            $this->produtoService->atualizarProduto(
                $id,
                $validated['nome'], // Dados do produto
                $request->input('estoque')          // Novo estoque
            );

            // Redireciona com mensagem de sucesso
            return redirect()->route('produtos.index')->with('success', 'Produto atualizado com sucesso!');
        } catch (\Exception $e) {
            // Em caso de erro
            return back()->withInput()->withErrors(['error' => 'Erro ao atualizar produto: ' . $e->getMessage()]);
        }
    }

    /**
     * Exclui um produto do banco de dados.
     * DELETE /produtos/{id}
     */
    public function destroy($id)
    {
        try {
            $this->produtoService->excluirProduto($id);
            return redirect()->route('produtos.index')->with('success', 'Produto excluído com sucesso!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erro ao excluir produto: ' . $e->getMessage()]);
        }
    }

    /**
     * Adiciona um produto ao carrinho de compras.
     * POST /carrinho/adicionar
     */
    public function adicionarAoCarrinho(CarrinhoAddItemRequest $request)
    {
        $validated = $request->validated();
        $produtoId = $validated['produto_id'];
        $quantidade = $validated['quantidade'];
        $variacaoId = $validated['variacao_id'] ?? null; // Opcional

        try {
            // Chama o serviço do carrinho para adicionar o produto
            $this->carrinhoService->adicionarProduto($produtoId, $quantidade, $variacaoId);
            return redirect()->route('carrinho.ver')->with('success', 'Produto adicionado ao carrinho!');
        } catch (\Exception $e) {
            // Em caso de erro (ex: estoque insuficiente)
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove um produto do carrinho de compras.
     * POST /carrinho/remover
     */
    public function removerDoCarrinho(CarrinhoRemoveItemRequest $request)
    {
        $validated = $request->validated();
        $produtoId = $validated['produto_id'];
        $variacaoId = $validated['variacao_id']; // Opcional

        try {
            // Chama o serviço do carrinho para remover o produto
            $this->carrinhoService->adicionarProduto($produtoId, $quantidade, $variacaoId);
            return redirect()->route('carrinho.ver')->with('success', 'Produto adicionado ao carrinho!');
        } catch (\Exception $e) {
            // Em caso de erro (ex: estoque insuficiente)
            return back()->with('error', $e->getMessage());
        }
    }
}