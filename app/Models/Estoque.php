<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estoque extends Model
{
    use HasFactory;

    protected $table = 'estoque'; // Nome da tabela no banco de dados

    protected $fillable = [
        'produto_id',
        'variacao_id', // Pode ser nulo se o estoque for para o produto principal
        'quantidade',
    ];

    /**
     * Um registro de estoque pertence a um produto.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produto_id');
    }

    /**
     * Um registro de estoque pode pertencer a uma variação específica.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function variacao()
    {
        return $this->belongsTo(Variacao::class, 'variacao_id');
    }

    /**
     * Um registro de estoque pode estar em muitos itens de pedido.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function itensPedido()
    {
        return $this->hasMany(ItemPedido::class, 'variacao_id');
    }
}