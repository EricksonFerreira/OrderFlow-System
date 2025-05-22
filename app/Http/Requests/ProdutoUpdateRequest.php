<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProdutoUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true; // Mesma lógica de autorização do StoreRequest
    }

    /**
     * Get the validation rules that apply to the request.
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
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'nome.required' => 'O nome do produto é obrigatório para atualização.',
            'preco.required' => 'O preço do produto é obrigatório para atualização.',
            'estoque.required' => 'A quantidade em estoque é obrigatória para atualização.',
        ];
    }
}