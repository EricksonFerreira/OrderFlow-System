<?php

namespace App\Services;

use App\Models\Produto; // Usaremos o Model Produto diretamente
use App\Models\Estoque; // Usaremos o Model Estoque diretamente
use Illuminate\Support\Facades\DB; // Para transações de banco de dados

class ProdutoService
{
    /**
     * Lista todos os produtos.
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function listarTodosProdutos()
    {
        return Produto::all();
    }

    /**
     * Busca um produto pelo ID.
     * @param int $id
     * @return Produto|null
     */
    public function buscarProdutoPorId(int $id)
    {
        return Produto::find($id);
    }

    /**
     * Cadastra um novo produto e inicializa seu estoque.
     * @param array $dadosProduto Array com 'nome', 'preco', etc.
     * @param int $quantidadeEstoque Quantidade inicial de estoque.
     * @param array $variacoes Opcional: array de variações (ex: [{'nome': 'P', 'quantidade': 10}, ...])
     * @return Produto O produto recém-criado.
     * @throws \Exception Se ocorrer um erro no cadastro.
     */
    public function cadastrarProduto(array $dadosProduto, int $quantidadeEstoque, array $variacoes = []): Produto
    {
        DB::beginTransaction(); // Inicia uma transação
        try {
            // Cria o produto diretamente com o Model
            $produto = Produto::create($dadosProduto);

            // Cria o estoque associado ao produto (ou suas variações)
            if (empty($variacoes)) {
                // Se não houver variações, cria um único registro de estoque para o produto
                Estoque::create([
                    'produto_id' => $produto->id,
                    'quantidade' => $quantidadeEstoque,
                    'variacao_id' => null // Indica que não é uma variação específica
                ]);
            } else {
                // Se houver variações, cria um registro de estoque para cada uma
                // Nota: A gestão de variações (criando a variação em si) não está incluída aqui,
                // apenas a criação do estoque para uma variação já existente ou com ID temporário.
                foreach ($variacoes as $variacao) {
                    Estoque::create([
                        'produto_id' => $produto->id,
                        'quantidade' => $variacao['quantidade'],
                        'variacao_id' => $variacao['id']
                    ]);
                }
            }

            DB::commit();
            return $produto;

        } catch (\Exception $e) {
            DB::rollBack(); // Desfaz a transação em caso de erro
            throw new \Exception('Erro ao cadastrar produto e estoque: ' . $e->getMessage());
        }
    }

    /**
     * Atualiza um produto existente e seu estoque.
     * @param int $id ID do produto a ser atualizado.
     * @param array $dadosProduto Novos dados do produto.
     * @param int $novaQuantidadeEstoque Nova quantidade de estoque.
     * @return bool True se a atualização for bem-sucedida, false caso contrário.
     * @throws \Exception Se o produto não for encontrado ou ocorrer um erro na atualização.
     */
    public function atualizarProduto(int $id, array $dadosProduto, int $novaQuantidadeEstoque): bool
    {
        DB::beginTransaction();
        try {
            // Busca o produto e atualiza diretamente
            $produto = Produto::find($id);
            if (!$produto) {
                throw new \Exception('Produto não encontrado para atualização.');
            }
            $produto->update($dadosProduto);

            // Atualiza o estoque associado
            // Assumimos que há um único registro de estoque para o produto sem variação.
            // Para variações, a lógica seria mais complexa (buscar o estoque específico da variação).
            $estoque = Estoque::where('produto_id', $id)->whereNull('variacao_id')->first();
            if ($estoque) {
                $estoque->update(['quantidade' => $novaQuantidadeEstoque]);
            } else {
                // Se não existe estoque para o produto sem variação, podemos criar um
                Estoque::create([
                    'produto_id' => $id,
                    'quantidade' => $novaQuantidadeEstoque,
                    'variacao_id' => null
                ]);
            }

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Erro ao atualizar produto e estoque: ' . $e->getMessage());
        }
    }

    /**
     * Exclui um produto e seu estoque associado.
     * @param int $id ID do produto a ser excluído.
     * @return bool True se a exclusão for bem-sucedida, false caso contrário.
     * @throws \Exception Se o produto não for encontrado ou ocorrer um erro na exclusão.
     */
    public function excluirProduto(int $id): bool
    {
        DB::beginTransaction();
        try {
            // Exclui o estoque associado ao produto primeiro
            Estoque::where('produto_id', $id)->delete();

            // Exclui o produto
            $deleted = Produto::destroy($id);

            if (!$deleted) {
                throw new \Exception('Produto não encontrado para exclusão.');
            }

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Erro ao excluir produto e estoque: ' . $e->getMessage());
        }
    }
}