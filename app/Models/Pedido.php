<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $table = 'pedidos'; // Nome da tabela no banco de dados

    protected $fillable = [
        'cliente_nome',
        'cliente_email',
        'endereco_cep',
        'endereco_rua',
        'endereco_numero',
        'endereco_bairro',
        'endereco_cidade',
        'endereco_estado',
        'valor_subtotal',
        'valor_frete',
        'valor_desconto',
        'valor_total',
        'status', // Ex: 'pendente', 'pago', 'cancelado', 'processando', 'enviado', 'entregue'
    ];

    /**
     * Um pedido tem muitos itens de pedido.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function itens()
    {
        return $this->hasMany(ItemPedido::class, 'pedido_id');
    }

    // Você pode adicionar métodos de acessor para formatar valores
    // public function getValorTotalFormatadoAttribute()
    // {
    //     return 'R$ ' . number_format($this->valor_total, 2, ',', '.');
    // }
}