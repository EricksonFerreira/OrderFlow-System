<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CepConsultaRequest extends FormRequest
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
            'cep' => 'required|string|regex:/^\d{5}-?\d{3}$/', // Formato 99999-999 ou 99999999
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
            'cep.required' => 'O CEP é obrigatório.',
            'cep.regex' => 'O CEP deve ter o formato 99999-999 ou 99999999.',
        ];
    }
}