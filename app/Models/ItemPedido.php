<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemPedido extends Model
{
    use HasFactory;

    protected $table = 'itens_pedido'; // Nome da tabela no banco de dados

    protected $fillable = [
        'pedido_id',
        'produto_id',
        'variacao_id',
        'quantidade',
        'preco_unitario',
        'nome_produto',
    ];

    protected $casts = [
        'quantidade' => 'integer',
        'preco_unitario' => 'decimal:2',
    ];

    /**
     * Um item de pedido pertence a um pedido.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'pedido_id');
    }

    /**
     * Um item de pedido pertence a um produto.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produto_id');
    }

    /**
     * Um item de pedido pode se referir a uma variação específica.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function variacao()
    {
        return $this->belongsTo(Variacao::class, 'variacao_id');
    }
}