<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class CupomValidoRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'cupom_codigo' => 'required|string',
            'subtotal' => 'required|numeric',
        ];
    }
}
