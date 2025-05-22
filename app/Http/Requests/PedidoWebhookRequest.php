<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PedidoWebhookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Webhooks frequentemente precisam de tokens de segurança ou IPs permitidos.
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
            'id' => 'required|integer|exists:pedidos,id', // O ID do pedido deve existir na sua tabela 'pedidos'
            'status' => 'required|string|in:pendente,pago,cancelado,processando,enviado,entregue', // Lista de status permitidos
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
            'id.required' => 'O ID do pedido é obrigatório.',
            'id.exists' => 'O pedido com o ID fornecido não foi encontrado.',
            'status.required' => 'O status do pedido é obrigatório.',
            'status.in' => 'O status fornecido não é válido.',
        ];
    }
}