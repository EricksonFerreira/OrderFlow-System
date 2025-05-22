<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PedidoFinalizeRequest extends FormRequest
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
            'nome_cliente' => 'required|string|max:255',
            'email_cliente' => 'required|email|max:255',
            'cep' => 'required|string|min:8|max:9', // Permite 8 ou 9 (com hífen)
            'endereco' => 'required|string|max:255',
            'numero' => 'required|string|max:20',
            'bairro' => 'required|string|max:255',
            'cidade' => 'required|string|max:255',
            'estado' => 'required|string|max:2', // UF (ex: SP, RJ)
            'cupom_codigo' => 'nullable|string|max:50',
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
            'nome_cliente.required' => 'O nome do cliente é obrigatório.',
            'email_cliente.required' => 'O e-mail do cliente é obrigatório.',
            'email_cliente.email' => 'O e-mail do cliente deve ser um endereço de e-mail válido.',
            'cep.required' => 'O CEP é obrigatório.',
            'cep.min' => 'O CEP deve ter 8 dígitos (sem hífen) ou 9 (com hífen).',
            'endereco.required' => 'O endereço é obrigatório.',
            'numero.required' => 'O número do endereço é obrigatório.',
            'bairro.required' => 'O bairro é obrigatório.',
            'cidade.required' => 'A cidade é obrigatória.',
            'estado.required' => 'O estado é obrigatório.',
            'estado.max' => 'O estado deve ter no máximo 2 caracteres (UF).',
        ];
    }
}