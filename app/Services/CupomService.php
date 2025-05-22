<?php

namespace App\Services;

use App\Models\Cupom;
use Carbon\Carbon;

class CupomService
{
    /**
     * Lista todos os cupons.
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function listarTodosCupons()
    {
        return Cupom::all();
    }

    /**
     * Busca um cupom pelo ID.
     * @param int $id
     * @return Cupom|null
     */
    public function buscarCupomPorId(int $id)
    {
        return Cupom::find($id);
    }

    /**
     * Cadastra um novo cupom.
     * @param array $dadosCupom Dados do cupom (codigo, desconto, tipo_desconto, valor_minimo, data_validade).
     * @return Cupom O cupom recém-criado.
     * @throws \Exception Se o código do cupom já existir.
     */
    public function cadastrarCupom(array $dadosCupom): Cupom
    {
        if (Cupom::where('codigo', $dadosCupom['codigo'])->exists()) {
            throw new \Exception('O código do cupom já existe.');
        }
        return Cupom::create($dadosCupom);
    }

    /**
     * Atualiza um cupom existente.
     * @param int $id ID do cupom a ser atualizado.
     * @param array $dadosCupom Novos dados do cupom.
     * @return bool True se a atualização for bem-sucedida, false caso contrário.
     * @throws \Exception Se o cupom não for encontrado ou o código já existir para outro cupom.
     */
    public function atualizarCupom(int $id, array $dadosCupom): bool
    {
        $cupom = Cupom::find($id);
        if (!$cupom) {
            throw new \Exception('Cupom não encontrado para atualização.');
        }

        // Verifica se o novo código já pertence a outro cupom
        if (isset($dadosCupom['codigo']) && $dadosCupom['codigo'] !== $cupom->codigo) {
            if (Cupom::where('codigo', $dadosCupom['codigo'])->where('id', '!=', $id)->exists()) {
                throw new \Exception('O novo código do cupom já existe em outro cupom.');
            }
        }

        return $cupom->update($dadosCupom);
    }

    /**
     * Exclui um cupom.
     * @param int $id ID do cupom a ser excluído.
     * @return bool True se a exclusão for bem-sucedida, false caso contrário.
     */
    public function excluirCupom(int $id): bool
    {
        return Cupom::destroy($id);
    }

    /**
     * Valida um cupom com base no código, validade e valor mínimo.
     * @param string $codigoCupom
     * @param float $subtotalCarrinho
     * @return Cupom|null O objeto Cupom se válido, null caso contrário.
     */
    public function validarCupom(string $codigoCupom, float $subtotalCarrinho): ?Cupom
    {
        $cupom = Cupom::where('codigo', $codigoCupom)->first();

        if (!$cupom) {
            return null; // Cupom não encontrado
        }

        // Verifica a validade do cupom (data_validade)
        if ($cupom->data_validade && Carbon::parse($cupom->data_validade)->isPast()) {
            return null;
        }

        if ($cupom->valor_minimo && $subtotalCarrinho < $cupom->valor_minimo) {
            return null;
        }

        return $cupom;
    }

    /**
     * Calcula o valor do desconto de um cupom sobre um subtotal.
     * @param Cupom $cupom
     * @param float $subtotal
     * @return float O valor do desconto a ser aplicado.
     */
    public function calcularDesconto(Cupom $cupom, float $subtotal): float
    {
        if ($cupom->tipo_desconto === 'porcentagem') {
            $desconto = ($subtotal * $cupom->desconto) / 100;
            return min($desconto, $subtotal);
        } elseif ($cupom->tipo_desconto === 'fixo') {
            return min($cupom->desconto, $subtotal);
        }
        return 0;
    }
}