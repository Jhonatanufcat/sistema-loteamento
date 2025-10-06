<!-- resources/views/lotes/show.blade.php -->
@extends('layouts.app')

@section('title', 'Detalhes do Lote')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Detalhes do Lote</h1>
            </div>
            <div class="col-sm-6">
                <a href="{{ route('lotes.index') }}" class="btn btn-secondary float-right">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Informações do Lote</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th>Número:</th>
                                <td>{{ $lote->numero }}</td>
                            </tr>
                            <tr>
                                <th>Quadra:</th>
                                <td>{{ $lote->quadra }}</td>
                            </tr>
                            <tr>
                                <th>Área:</th>
                                <td>{{ number_format($lote->area, 2, ',', '.') }} m²</td>
                            </tr>
                            <tr>
                                <th>Valor:</th>
                                <td>R$ {{ number_format($lote->valor, 2, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td>
                                    <span class="badge badge-{{ $lote->status == 'disponivel' ? 'success' : ($lote->status == 'reservado' ? 'warning' : 'danger') }}">
                                        {{ $lote->status_formatado }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Detalhes Adicionais</h3>
                    </div>
                    <div class="card-body">
                        <h5>Características:</h5>
                        <p>{{ $lote->caracteristicas ?? 'Nenhuma característica informada.' }}</p>
                        
                        <h5>Observações:</h5>
                        <p>{{ $lote->observacoes ?? 'Nenhuma observação.' }}</p>
                    </div>
                </div>
                
                @if($lote->venda)
                <div class="card mt-3">
                    <div class="card-header">
                        <h3 class="card-title">Informações da Venda</h3>
                    </div>
                    <div class="card-body">
                        <p><strong>Cliente:</strong> {{ $lote->venda->cliente->nome }}</p>
                        <p><strong>Data da Venda:</strong> {{ $lote->venda->data_venda->format('d/m/Y') }}</p>
                        <p><strong>Valor Total:</strong> R$ {{ number_format($lote->venda->valor_total, 2, ',', '.') }}</p>
                        <a href="{{ route('vendas.show', $lote->venda->id) }}" class="btn btn-info btn-sm">
                            Ver Venda
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12">
                <a href="{{ route('lotes.edit', $lote->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Editar Lote
                </a>
                
                @if(!$lote->venda && $lote->status == 'disponivel')
                <a href="{{ route('vendas.create') }}?lote_id={{ $lote->id }}" class="btn btn-success">
                    <i class="fas fa-handshake"></i> Vender Lote
                </a>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection