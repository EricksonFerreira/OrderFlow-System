@extends('layouts.app')

@section('title', 'Gerenciar Cupons')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Gerenciar Cupons</h1>
        <a href="{{ route('cupons.create') }}" class="btn btn-success">Adicionar Novo Cupom</a>
    </div>

    @if ($cupons->isEmpty())
        <div class="alert alert-info">Nenhum cupom cadastrado ainda.</div>
    @else
        <div class="table-responsive">
            <table class="table table-striped table-hover mt-4">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Desconto</th>
                        <th>Tipo</th>
                        <th>Valor Mínimo</th>
                        <th>Validade</th>
                        <th>Ativo</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cupons as $cupom)
                        <tr>
                            <td>{{ $cupom->codigo }}</td>
                            <td>
                                @if ($cupom->tipo_desconto === 'porcentagem')
                                    {{ number_format($cupom->desconto, 0, ',', '.') }}%
                                @else
                                    R$ {{ number_format($cupom->desconto, 2, ',', '.') }}
                                @endif
                            </td>
                            <td>{{ ucfirst($cupom->tipo_desconto) }}</td>
                            <td>R$ {{ number_format($cupom->valor_minimo, 2, ',', '.') }}</td>
                            <td>
                                @if ($cupom->data_validade)
                                    {{ \Carbon\Carbon::parse($cupom->data_validade)->format('d/m/Y') }}
                                    @if (\Carbon\Carbon::parse($cupom->data_validade)->isPast())
                                        <span class="badge bg-danger">Expirado</span>
                                    @endif
                                @else
                                    Nunca expira
                                @endif
                            </td>
                            <td>
                                @if ($cupom->ativo)
                                    <span class="badge bg-success">Sim</span>
                                @else
                                    <span class="badge bg-danger">Não</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('cupons.edit', $cupom->id) }}" class="btn btn-warning btn-sm">Editar</a>
                                <form action="{{ route('cupons.destroy', $cupom->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir este cupom?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection