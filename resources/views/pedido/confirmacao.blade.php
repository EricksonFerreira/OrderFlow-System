@extends('layouts.app')

@section('title', 'Pedido Confirmado!')

@section('content')
    <div class="card text-center shadow-sm">
        <div class="card-header bg-success text-white">
            <h1 class="card-title mb-0">Pedido Confirmado com Sucesso!</h1>
        </div>
        <div class="card-body">
            <p class="lead">Obrigado por sua compra, {{ $pedido->cliente_nome }}!</p>
            <p>Seu pedido de número **#{{ $pedido->id }}** foi realizado com sucesso.</p>
            <p>Você receberá um e-mail de confirmação em **{{ $pedido->cliente_email }}** com todos os detalhes.</p>

            <hr>

            <h3 class="mb-3">Detalhes do Pedido</h3>
            <ul class="list-group list-group-flush mb-4">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Subtotal:
                    <span class="fw-bold">R$ {{ number_format($pedido->valor_subtotal, 2, ',', '.') }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Frete:
                    <span class="fw-bold">R$ {{ number_format($pedido->valor_frete, 2, ',', '.') }}</span>
                </li>
                @if ($pedido->valor_desconto > 0)
                    <li class="list-group-item d-flex justify-content-between align-items-center text-danger">
                        Desconto:
                        <span class="fw-bold">- R$ {{ number_format($pedido->valor_desconto, 2, ',', '.') }}</span>
                    </li>
                @endif
                <li class="list-group-item d-flex justify-content-between align-items-center bg-light fs-4">
                    Total:
                    <span class="fw-bold text-primary">R$ {{ number_format($pedido->valor_total, 2, ',', '.') }}</span>
                </li>
            </ul>

            <p>Endereço de Entrega: {{ $pedido->endereco_rua }}, {{ $pedido->endereco_numero }} - {{ $pedido->endereco_bairro }}, {{ $pedido->endereco_cidade }} - {{ $pedido->endereco_estado }} CEP: {{ $pedido->endereco_cep }}</p>

            <p class="mt-4">
                Acompanhe o status do seu pedido acessando a sua conta (se aplicável) ou aguarde novas atualizações por e-mail.
            </p>
        </div>
        <div class="card-footer">
            <a href="{{ route('home') }}" class="btn btn-primary btn-lg">Continuar Comprando</a>
        </div>
    </div>
@endsection