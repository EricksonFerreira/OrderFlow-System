<?php

namespace App\Services;

class FreteService
{
    /**
     * Calcula o valor do frete com base no subtotal do pedido.
     * @param float $subtotal
     * @return float Valor do frete.
     */
    public function calcularFrete(float $subtotal): float
    {
        if ($subtotal >= 52.00 && $subtotal <= 166.59) {
            return 15.00;
        } elseif ($subtotal > 200.00) {
            return 0.00;
        } else {
            return 20.00;
        }
    }
}