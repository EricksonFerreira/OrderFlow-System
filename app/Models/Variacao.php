<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variacao extends Model
{
    use HasFactory;

    protected $table = 'variacoes'; // Nome da tabela no banco de dados

    protected $fillable = [
        'produto_id',
        'nome',
        'sku',
        'preco_adicional',
        'imagem_id',
    ];

    /**
     * Uma variação pertence a um produto.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produto_id');
    }

    /**
     * Uma variação pode ter um estoque.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function estoque()
    {
        return $this->hasOne(Estoque::class, 'variacao_id');
    }

    /**
     * Uma variação pode aparecer em muitos itens de pedido.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function itensPedido()
    {
        return $this->hasMany(ItemPedido::class, 'variacao_id');
    }

    /**
     * Uma variação tem um registro de estoque (ou mais, se estoque for por armazém).
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function estoque()
    {
        return $this->hasOne(Estoque::class, 'variacao_id');
    }
}