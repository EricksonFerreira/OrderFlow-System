@extends('layouts.app')

@section('title', 'Meu Carrinho')

@section('content')
    <h1>Meu Carrinho</h1>

    @if ($itensCarrinho && count($itensCarrinho) > 0)
        <table class="table table-striped table-hover mt-4">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Preço Unitário</th>
                    <th>Quantidade</th>
                    <th>Total</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($itensCarrinho as $itemKey => $item)
                    <tr>
                        <td>
                            {{ $item['nome'] }}
                            @if ($item['variacao_id'])
                                <small class="text-muted">(Variação: {{ $item['variacao_id'] }})</small> {{-- Em um caso real, buscaria o nome da variação --}}
                            @endif
                        </td>
                        <td>R$ {{ number_format($item['preco'], 2, ',', '.') }}</td>
                        <td>{{ $item['quantidade'] }}</td>
                        <td>R$ {{ number_format($item['preco'] * $item['quantidade'], 2, ',', '.') }}</td>
                        <td>
                            <form action="{{ route('carrinho.remover') }}" method="POST">
                                @csrf
                                <input type="hidden" name="produto_id" value="{{ $item['produto_id'] }}">
                                @if ($item['variacao_id'])
                                    <input type="hidden" name="variacao_id" value="{{ $item['variacao_id'] }}">
                                @endif
                                <button type="submit" class="btn btn-danger btn-sm">Remover</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-end fw-bold">Subtotal:</td>
                    <td class="fw-bold">R$ {{ number_format($subtotal, 2, ',', '.') }}</td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="3" class="text-end fw-bold">Frete:</td>
                    <td class="fw-bold">R$ {{ number_format($valorFrete, 2, ',', '.') }}</td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="3" class="text-end fw-bold">Desconto de Cupom:</td>
                    <td class="fw-bold"><span id="valor-desconto">0,00</span></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="3" class="text-end fw-bold fs-5">Total do Pedido:</td>
                    <td class="fw-bold fs-5 text-primary">R$ <span id="total-pedido">{{ number_format($subtotal + $valorFrete, 2, ',', '.') }}</span></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>

        <hr>

        <h2>Finalizar Pedido</h2>
        <div class="card shadow-sm mt-3">
            <div class="card-body">
                <form action="{{ route('pedido.finalizar') }}" method="POST" id="finalizar-pedido-form">
                    @csrf
                    <h3>Dados do Cliente</h3>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nome_cliente" class="form-label">Nome Completo:</label>
                            <input type="text" class="form-control @error('nome_cliente') is-invalid @enderror" id="nome_cliente" name="nome_cliente" value="{{ old('nome_cliente') }}" required>
                            @error('nome_cliente') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email_cliente" class="form-label">E-mail:</label>
                            <input type="email" class="form-control @error('email_cliente') is-invalid @enderror" id="email_cliente" name="email_cliente" value="{{ old('email_cliente') }}" required>
                            @error('email_cliente') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <h3 class="mt-4">Endereço de Entrega</h3>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="cep" class="form-label">CEP:</label>
                            <input type="text" class="form-control @error('cep') is-invalid @enderror" id="cep" name="cep" value="{{ old('cep') }}" required maxlength="9" placeholder="99999-999">
                            @error('cep') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <button type="button" class="btn btn-sm btn-outline-secondary mt-2" id="consultar-cep">Consultar CEP</button>
                        </div>
                        <div class="col-md-8 mb-3">
                            <label for="endereco" class="form-label">Endereço:</label>
                            <input type="text" class="form-control @error('endereco') is-invalid @enderror" id="endereco" name="endereco" value="{{ old('endereco') }}" required>
                            @error('endereco') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="numero" class="form-label">Número:</label>
                            <input type="text" class="form-control @error('numero') is-invalid @enderror" id="numero" name="numero" value="{{ old('numero') }}" required>
                            @error('numero') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-5 mb-3">
                            <label for="bairro" class="form-label">Bairro:</label>
                            <input type="text" class="form-control @error('bairro') is-invalid @enderror" id="bairro" name="bairro" value="{{ old('bairro') }}" required>
                            @error('bairro') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="cidade" class="form-label">Cidade:</label>
                            <input type="text" class="form-control @error('cidade') is-invalid @enderror" id="cidade" name="cidade" value="{{ old('cidade') }}" required>
                            @error('cidade') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-1 mb-3">
                            <label for="estado" class="form-label">UF:</label>
                            <input type="text" class="form-control @error('estado') is-invalid @enderror" id="estado" name="estado" value="{{ old('estado') }}" required maxlength="2">
                            @error('estado') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <h3 class="mt-4">Cupom de Desconto</h3>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="cupom_codigo" class="form-label">Código do Cupom:</label>
                            <div class="input-group">
                                <input type="text" class="form-control @error('cupom_codigo') is-invalid @enderror" id="cupom_codigo" name="cupom_codigo" value="{{ old('cupom_codigo') }}" placeholder="Ex: MEUCUPOM">
                                <button class="btn btn-outline-secondary" type="button" id="aplicar-cupom">Aplicar Cupom</button>
                            </div>
                            @error('cupom_codigo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            @error('cupom') <div class="alert alert-warning mt-2">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success btn-lg mt-4">Confirmar Pedido</button>
                    <a href="{{ route('home') }}" class="btn btn-secondary btn-lg mt-4">Continuar Comprando</a>
                </form>
            </div>
        </div>
    @else
        <div class="alert alert-warning mt-4">Seu carrinho está vazio. <a href="{{ route('produtos.index') }}">Comece a comprar!</a></div>
    @endif
@endsection

@section('scripts_extra')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cepInput = document.getElementById('cep');
            const consultarCepBtn = document.getElementById('consultar-cep');
            const enderecoInput = document.getElementById('endereco');
            const bairroInput = document.getElementById('bairro');
            const cidadeInput = document.getElementById('cidade');
            const estadoInput = document.getElementById('estado');

            const cupomInput = document.getElementById('cupom_codigo');
            const aplicarCupomBtn = document.getElementById('aplicar-cupom');
            const valorDescontoSpan = document.getElementById('valor-desconto');
            const totalPedidoSpan = document.getElementById('total-pedido');

            const subtotal = {{ $subtotal }};
            const valorFrete = {{ $valorFrete }};
            let descontoAtual = 0; // Para controle do JS

            function formatCurrency(value) {
                return value.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
            }

            function updatePedidoTotal(tipo = 'fixo') {
                if(tipo === 'porcentagem') {
                    descontoAtual = subtotal * (descontoAtual / 100);
                } else {
                    descontoAtual = descontoAtual;
                }
                const total = (subtotal - descontoAtual) + valorFrete;
                totalPedidoSpan.textContent = formatCurrency(total).replace('R$', ''); // Remove R$ para ficar igual ao formato inicial
            }

            // Função para consultar CEP
            consultarCepBtn.addEventListener('click', function() {
                const cep = cepInput.value.replace(/\D/g, ''); // Remove não-dígitos

                if (cep.length !== 8) {
                    alert('Por favor, digite um CEP válido com 8 dígitos.');
                    return;
                }

                fetch('{{ route('cep.consultar') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ cep: cep })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert('Erro ao consultar CEP: ' + data.error);
                        // Limpa campos se houver erro
                        enderecoInput.value = '';
                        bairroInput.value = '';
                        cidadeInput.value = '';
                        estadoInput.value = '';
                    } else {
                        enderecoInput.value = data.logradouro || '';
                        bairroInput.value = data.bairro || '';
                        cidadeInput.value = data.localidade || '';
                        estadoInput.value = data.uf || '';
                    }
                })
                .catch(error => {
                    console.error('Erro na requisição AJAX:', error);
                    alert('Ocorreu um erro ao consultar o CEP.');
                });
            });

            // Função para aplicar cupom (simulada via JS, a validação real ocorre no backend)
            aplicarCupomBtn.addEventListener('click', function() {
                const cupomCodigo = cupomInput.value.trim();
                if (!cupomCodigo) {
                    alert('Por favor, digite um código de cupom.');
                    return;
                }


                fetch('{{ route('pedido.cupom-valido') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        cupom_codigo: cupomCodigo,
                        subtotal: subtotal
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.valido) {
                        descontoAtual = data.desconto;
                        if(data.tipo === 'porcentagem') {
                            valorDescontoSpan.textContent = data.desconto + '%';
                        } else {
                            valorDescontoSpan.textContent = formatCurrency(data.desconto).replace('R$', '');
                        }
                        
                        alert('Cupom ' + cupomCodigo + ' aplicado! Desconto de ' + valorDescontoSpan.textContent);
                        updatePedidoTotal(data.tipo);

                    } else {
                        // alert(data.message);
                        alert('O cupom não é válido ou não pode ser aplicado para este pedido.');
                        updatePedidoTotal();
                    }
                })
                .catch(error => {
                    console.error('Erro ao validar cupom:', error);
                    alert('O cupom não é válido ou não pode ser aplicado para este pedido.');
                });
            });
        });
    </script>
@endsection