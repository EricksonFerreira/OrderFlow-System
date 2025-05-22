<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class PedidoFinalizarRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'cep' => 'required|numeric|digits:8',
            'endereco' => 'required|string',
            'numero' => 'required|numeric',
            'bairro' => 'required|string',
            'cidade' => 'required|string',
            'estado' => 'required|string',
            'email_cliente' => 'required|email',
            'cupom_codigo' => 'nullable|string',
            'nome_cliente' => 'required|string',
        ];
    }
}
