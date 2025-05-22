<div>
    <h1>Confirmação de Pedido</h1>
    <p>Olá, {{ $pedido->cliente_nome }}!</p>
    <p>Seu pedido foi confirmado com sucesso!</p>
    <p>Detalhes do pedido:</p>
    <p>Valor Subtotal: R$ {{ number_format($pedido->valor_subtotal, 2, ',', '.') }}</p>
    <p>Valor Frete: R$ {{ number_format($pedido->valor_frete, 2, ',', '.') }}</p>
    @if ($pedido->valor_desconto > 0)
        <p>Valor Desconto: R$ {{ number_format($pedido->valor_desconto, 2, ',', '.') }}</p>
    @endif
    <p>Total: R$ {{ number_format($pedido->valor_total, 2, ',', '.') }}</p>
    <p>Obrigado por comprar conosco!</p>
</div>