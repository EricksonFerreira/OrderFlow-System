<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;

    // Define os campos que podem ser preenchidos em massa (mass assignable)
    protected $fillable = [
        'nome',
        'preco',
        'descricao', // Se você adicionar um campo de descrição no futuro
    ];

    /**
     * Um produto pode ter muitos registros de estoque (para ele mesmo ou suas variações).
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function estoques()
    {
        return $this->hasMany(Estoque::class, 'produto_id');
    }

    /**
     * Um produto pode aparecer em muitos itens de pedido.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function itensPedido()
    {
        return $this->hasMany(ItemPedido::class, 'produto_id');
    }

    /**
     * Se você tiver variações separadas, um produto pode ter muitas variações.
     * Exemplo: 'Camiseta' tem variações 'Tamanho P', 'Tamanho M', 'Tamanho G'.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function variacoes()
    {
        return $this->hasMany(Variacao::class, 'produto_id');
    }

    // Você pode adicionar métodos de acessor/mutator aqui se necessário,
    // por exemplo, para formatar o preço.
    // public function getPrecoFormatadoAttribute()
    // {
    //     return 'R$ ' . number_format($this->preco, 2, ',', '.');
    // }
}