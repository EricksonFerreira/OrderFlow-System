<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CarrinhoRemoveItemRequest extends FormRequest
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
            'produto_id' => 'required|integer',
            'variacao_id' => 'nullable|integer', // Opcional, se o item tiver variação
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
            'produto_id.required' => 'O ID do produto é obrigatório para remover do carrinho.',
            'produto_id.integer' => 'O ID do produto deve ser um número inteiro.',
        ];
    }
}