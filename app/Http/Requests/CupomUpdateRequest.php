<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CupomUpdateRequest extends FormRequest
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
        $cupomId = $this->route('cupom'); // Pega o ID do cupom da rota

        return [
            'codigo' => 'required|string|max:50|unique:cupons,codigo,'.$cupomId,
            'desconto' => 'required|numeric|min:0',
            'tipo_desconto' => 'required|in:porcentagem,fixo',
            'valor_minimo' => 'nullable|numeric|min:0',
            'data_validade' => 'nullable|date|after_or_equal:today',
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
            'codigo.unique' => 'Este código de cupom já está em uso por outro cupom.',
        ];
    }
}