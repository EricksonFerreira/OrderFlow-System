@extends('layouts.app')

@section('title', 'Editar Produto: ' . $produto->nome)

@section('content')
    <h1>Editar Produto: {{ $produto->nome }}</h1>

    <div class="card shadow-sm mt-4">
        <div class="card-body">
            <form action="{{ route('produtos.update', $produto->id) }}" method="POST">
                @csrf
                @method('PUT') {{-- Indica que é uma requisição PUT para o método update --}}

                <div class="mb-3">
                    <label for="nome" class="form-label">Nome do Produto:</label>
                    <input type="text" class="form-control @error('nome') is-invalid @enderror" id="nome" name="nome" value="{{ old('nome', $produto->nome) }}" required>
                    @error('nome')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="preco" class="form-label">Preço (R$):</label>
                    <input type="number" step="0.01" class="form-control @error('preco') is-invalid @enderror" id="preco" name="preco" value="{{ old('preco', $produto->preco) }}" required min="0.01">
                    @error('preco')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="estoque" class="form-label">Estoque Atual:</label>
                    {{-- Assumimos que o produto tem apenas um registro de estoque para simplificar a exibição --}}
                    {{-- Em um cenário com variações, você precisaria listar os estoques por variação --}}
                    <input type="number" class="form-control @error('estoque') is-invalid @enderror" id="estoque" name="estoque" value="{{ old('estoque', $produto->estoques->sum('quantidade')) }}" required min="0">
                    @error('estoque')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="descricao" class="form-label">Descrição (Opcional):</label>
                    <textarea class="form-control @error('descricao') is-invalid @enderror" id="descricao" name="descricao" rows="3">{{ old('descricao', $produto->descricao) }}</textarea>
                    @error('descricao')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Atualizar Produto</button>
                <a href="{{ route('produtos.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
@endsection