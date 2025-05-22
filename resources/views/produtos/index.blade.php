@extends('layouts.app')

@section('title', 'Nossos Produtos')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Produtos Disponíveis</h1>
        <a href="{{ route('produtos.create') }}" class="btn btn-success">Adicionar Novo Produto</a>
    </div>

    @if ($produtos->isEmpty())
        <div class="alert alert-info">Nenhum produto cadastrado ainda.</div>
    @else
        <div class="row row-cols-1 row-cols-md-3 g-4">
            @foreach ($produtos as $produto)
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        {{-- <img src="https://via.placeholder.com/150" class="card-img-top" alt="{{ $produto->nome }}"> --}}
                        <div class="card-body">
                            <h5 class="card-title">{{ $produto->nome }}</h5>
                            <p class="card-text text-muted">
                                Estoque: {{ $produto->estoques->sum('quantidade') }}
                            </p>
                            <p class="card-text fs-4 fw-bold text-primary">R$ {{ number_format($produto->preco, 2, ',', '.') }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('produtos.show', $produto->id) }}" class="btn btn-info btn-sm">Ver Detalhes</a>
                                <form action="{{ route('carrinho.adicionar') }}" method="POST" class="d-flex">
                                    @csrf
                                    <input type="hidden" name="produto_id" value="{{ $produto->id }}">
                                    <input type="number" name="quantidade" value="1" min="1" class="form-control form-control-sm me-2" style="width: 70px;">
                                    <button type="submit" class="btn btn-primary btn-sm">Adicionar ao Carrinho</button>
                                </form>
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-end">
                            <a href="{{ route('produtos.edit', $produto->id) }}" class="btn btn-warning btn-sm me-2">Editar</a>
                            <form action="{{ route('produtos.destroy', $produto->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este produto?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection