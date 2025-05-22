<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CupomStoreRequest extends FormRequest
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
            'codigo' => 'required|string|unique:cupons,codigo|max:50',
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
            'codigo.required' => 'O código do cupom é obrigatório.',
            'codigo.unique' => 'Este código de cupom já está em uso.',
            'desconto.required' => 'O valor do desconto é obrigatório.',
            'desconto.numeric' => 'O desconto deve ser um número.',
            'desconto.min' => 'O desconto não pode ser negativo.',
            'tipo_desconto.required' => 'O tipo de desconto é obrigatório.',
            'tipo_desconto.in' => 'O tipo de desconto deve ser porcentagem ou fixo.',
            'valor_minimo.numeric' => 'O valor mínimo deve ser um número.',
            'data_validade.date' => 'A data de validade deve ser uma data válida.',
            'data_validade.after_or_equal' => 'A data de validade não pode ser no passado.',
        ];
    }
}