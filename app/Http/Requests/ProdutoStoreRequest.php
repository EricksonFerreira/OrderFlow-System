<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProdutoStoreRequest extends FormRequest
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
     * Obtém as regras de validação que se aplicam à requisição.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'nome' => 'required|string|max:255',
            'preco' => 'required|numeric|min:0.01',
            'estoque' => 'required|integer|min:0',
        ];
    }

    /**
     * Get custom messages for validation errors.
     * Obtém mensagens personalizadas para erros de validação.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'nome.required' => 'O nome do produto é obrigatório.',
            'preco.required' => 'O preço do produto é obrigatório.',
            'preco.numeric' => 'O preço deve ser um número.',
            'preco.min' => 'O preço deve ser no mínimo R$0.01.',
            'estoque.required' => 'A quantidade em estoque é obrigatória.',
            'estoque.integer' => 'O estoque deve ser um número inteiro.',
            'estoque.min' => 'O estoque não pode ser negativo.',
        ];
    }
}