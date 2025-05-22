<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CupomService; // Precisaremos deste serviço
use App\Http\Requests\CupomStoreRequest;
use App\Http\Requests\CupomUpdateRequest;

class CupomController extends Controller
{
    protected $cupomService;

    public function __construct(CupomService $cupomService)
    {
        $this->cupomService = $cupomService;
    }

    /**
     * Exibe a lista de todos os cupons.
     * GET /cupons
     */
    public function index()
    {
        $cupons = $this->cupomService->listarTodosCupons();
        return view('cupons.index', compact('cupons'));
    }

    /**
     * Mostra o formulário para criar um novo cupom.
     * GET /cupons/criar
     */
    public function create()
    {
        return view('cupons.create');
    }

    /**
     * Armazena um novo cupom no banco de dados.
     * POST /cupons
     */
    public function store(CupomStoreRequest $request)
    {
        $validated = $request->validated();

        try {
            $this->cupomService->cadastrarCupom($validated);
            return redirect()->route('cupons.index')->with('success', 'Cupom cadastrado com sucesso!');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Erro ao cadastrar cupom: ' . $e->getMessage()]);
        }
    }

    /**
     * Mostra o formulário para editar um cupom existente.
     * GET /cupons/{id}/editar
     */
    public function edit($id)
    {
        $cupom = $this->cupomService->buscarCupomPorId($id);
        if (!$cupom) {
            return redirect()->route('cupons.index')->with('error', 'Cupom não encontrado.');
        }
        return view('cupons.edit', compact('cupom'));
    }

    /**
     * Atualiza um cupom existente no banco de dados.
     * PUT/PATCH /cupons/{id}
     */
    public function update(CupomUpdateRequest $request, $id)
    {
        $validated = $request->validated();

        try {
            $this->cupomService->atualizarCupom($id, $validated);
            return redirect()->route('cupons.index')->with('success', 'Cupom atualizado com sucesso!');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Erro ao atualizar cupom: ' . $e->getMessage()]);
        }
    }

    /**
     * Exclui um cupom do banco de dados.
     * DELETE /cupons/{id}
     */
    public function destroy($id)
    {
        try {
            $this->cupomService->excluirCupom($id);
            return redirect()->route('cupons.index')->with('success', 'Cupom excluído com sucesso!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erro ao excluir cupom: ' . $e->getMessage()]);
        }
    }

}