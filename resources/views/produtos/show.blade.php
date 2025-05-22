@extends('layouts.app')

@section('title', $produto->nome)

@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h1 class="card-title mb-0">{{ $produto->nome }}</h1>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    {{-- <img src="https://via.placeholder.com/300" class="img-fluid rounded" alt="{{ $produto->nome }}"> --}}
                    <p class="text-muted mt-2">Imagem do Produto (placeholder)</p>
                </div>
                <div class="col-md-8">
                    <p class="lead">{{ $produto->descricao ?? 'Nenhuma descrição disponível.' }}</p>
                    <hr>
                    <p class="fs-3 fw-bold text-success">Preço: R$ {{ number_format($produto->preco, 2, ',', '.') }}</p>
                    <p class="fs-5">Estoque Disponível: <span class="badge bg-info text-dark">{{ $produto->estoques->sum('quantidade') }}</span></p>

                    <form action="{{ route('carrinho.adicionar') }}" method="POST" class="d-flex align-items-center mt-4">
                        @csrf
                        <input type="hidden" name="produto_id" value="{{ $produto->id }}">
                        <label for="quantidade" class="form-label me-2 mb-0">Quantidade:</label>
                        <input type="number" name="quantidade" id="quantidade" value="1" min="1" class="form-control me-3" style="width: 80px;">
                        <button type="submit" class="btn btn-success btn-lg">Adicionar ao Carrinho</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-between">
            <a href="{{ route('produtos.index') }}" class="btn btn-secondary">Voltar para Produtos</a>
            <div class="d-flex">
                <a href="{{ route('produtos.edit', $produto->id) }}" class="btn btn-warning me-2">Editar Produto</a>
                <form action="{{ route('produtos.destroy', $produto->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este produto?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Excluir Produto</button>
                </form>
            </div>
        </div>
    </div>
@endsection