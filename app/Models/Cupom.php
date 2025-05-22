<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cupom extends Model
{
    use HasFactory;

    protected $table = 'cupons'; // Nome da tabela no banco de dados

    protected $fillable = [
        'codigo',
        'desconto',      // Valor do desconto (ex: 10 para 10% ou 15.00 para R$15)
        'tipo_desconto', // 'porcentagem' ou 'fixo'
        'valor_minimo',  // Valor mínimo do pedido para aplicar o cupom
        'data_validade', // Data até quando o cupom é válido
        'ativo',         // Booleano: 1 para ativo, 0 para inativo
    ];

    // Converte a data_validade para um objeto Carbon automaticamente
    protected $dates = [
        'data_validade',
    ];

    // Você pode adicionar métodos aqui para verificar a validade do cupom
    // public function isValid()
    // {
    //     return $this->ativo && (!$this->data_validade || $this->data_validade->isFuture());
    // }
}