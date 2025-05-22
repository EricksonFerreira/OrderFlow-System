<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CarrinhoAddItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'produto_id' => 'required|integer|exists:produtos,id', // Garante que o produto existe na tabela 'produtos'
            'quantidade' => 'required|integer|min:1',
            'variacao_id' => 'nullable|integer|exists:variacoes,id', // Se for usar variações
        ];
    }

    /**
     * Get custom messages for validation errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'produto_id.required' => 'O ID do produto é obrigatório.',
            'produto_id.exists' => 'O produto selecionado não existe.',
            'quantidade.required' => 'A quantidade é obrigatória.',
            'quantidade.integer' => 'A quantidade deve ser um número inteiro.',
            'quantidade.min' => 'A quantidade mínima para adicionar ao carrinho é 1.',
            'variacao_id.exists' => 'A variação selecionada não existe.',
        ];
    }
}