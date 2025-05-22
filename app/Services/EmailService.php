<?php

namespace App\Services;

use App\Models\Pedido;
use Illuminate\Support\Facades\Mail;
use App\Mail\PedidoConfirmacao;

class EmailService
{
    /**
     * Envia um e-mail de confirmação de pedido para o cliente.
     * @param Pedido $pedido O objeto do pedido.
     * @param string $emailCliente O endereço de e-mail do cliente.
     * @return void
     * @throws \Exception Em caso de falha no envio do e-mail.
     */
    public function enviarEmailConfirmacao(Pedido $pedido, string $emailCliente): void
    {
        try {
            Mail::to($emailCliente)->send(new PedidoConfirmacao($pedido));
        } catch (\Exception $e) {
            \Log::error('Erro ao enviar e-mail de confirmação para ' . $emailCliente . ': ' . $e->getMessage());
        }
    }
}