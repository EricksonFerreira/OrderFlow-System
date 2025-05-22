@extends('layouts.app')

@section('title', 'Criar Novo Cupom')

@section('content')
    <h1>Criar Novo Cupom</h1>

    <div class="card shadow-sm mt-4">
        <div class="card-body">
            <form action="{{ route('cupons.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="codigo" class="form-label">Código do Cupom:</label>
                    <input type="text" class="form-control @error('codigo') is-invalid @enderror" id="codigo" name="codigo" value="{{ old('codigo') }}" required>
                    @error('codigo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label for="desconto" class="form-label">Valor do Desconto:</label>
                    <input type="number" step="0.01" class="form-control @error('desconto') is-invalid @enderror" id="desconto" name="desconto" value="{{ old('desconto') }}" required min="0">
                    @error('desconto') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label for="tipo_desconto" class="form-label">Tipo de Desconto:</label>
                    <select class="form-select @error('tipo_desconto') is-invalid @enderror" id="tipo_desconto" name="tipo_desconto" required>
                        <option value="porcentagem" {{ old('tipo_desconto') == 'porcentagem' ? 'selected' : '' }}>Porcentagem (%)</option>
                        <option value="fixo" {{ old('tipo_desconto') == 'fixo' ? 'selected' : '' }}>Valor Fixo (R$)</option>
                    </select>
                    @error('tipo_desconto') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label for="valor_minimo" class="form-label">Valor Mínimo do Pedido (Opcional):</label>
                    <input type="number" step="0.01" class="form-control @error('valor_minimo') is-invalid @enderror" id="valor_minimo" name="valor_minimo" value="{{ old('valor_minimo') }}" min="0">
                    @error('valor_minimo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label for="data_validade" class="form-label">Data de Validade (Opcional):</label>
                    <input type="date" class="form-control @error('data_validade') is-invalid @enderror" id="data_validade" name="data_validade" value="{{ old('data_validade') }}">
                    @error('data_validade') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" value="1" id="ativo" name="ativo" {{ old('ativo', true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="ativo">
                        Ativo
                    </label>
                </div>

                <button type="submit" class="btn btn-primary">Criar Cupom</button>
                <a href="{{ route('cupons.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
@endsection