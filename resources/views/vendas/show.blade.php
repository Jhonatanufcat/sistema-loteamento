<!-- resources/views/vendas/show.blade.php -->
@extends('layouts.app')

@section('title', 'Detalhes da Venda')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Detalhes da Venda</h1>
            </div>
            <div class="col-sm-6">
                <a href="{{ route('vendas.index') }}" class="btn btn-secondary float-right">
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
                        <h3 class="card-title">Informações da Venda</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th>Data da Venda:</th>
                                <td>{{ $venda->data_venda->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <th>Cliente:</th>
                                <td>{{ $venda->cliente->nome }}</td>
                            </tr>
                            <tr>
                                <th>Lote:</th>
                                <td>{{ $venda->lote->numero }} - Quadra {{ $venda->lote->quadra }}</td>
                            </tr>
                            <tr>
                                <th>Valor Total:</th>
                                <td>R$ {{ number_format($venda->valor_total, 2, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th>Entrada:</th>
                                <td>R$ {{ number_format($venda->entrada, 2, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th>Parcelas:</th>
                                <td>{{ $venda->parcelas }}x</td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td>
                                    <span class="badge badge-{{ $venda->status == 'ativa' ? 'success' : ($venda->status == 'quitada' ? 'info' : 'secondary') }}">
                                        {{ $venda->status }}
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
                        <h3 class="card-title">Ações</h3>
                    </div>
                    <div class="card-body">
                        @if(!$venda->contrato)
                        <form action="{{ route('vendas.gerar-contrato', $venda->id) }}" method="POST" class="mb-2">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-file-contract"></i> Gerar Contrato
                            </button>
                        </form>
                        @else
                        <a href="{{ route('contratos.show', $venda->contrato->id) }}" class="btn btn-info btn-block mb-2">
                            <i class="fas fa-eye"></i> Ver Contrato
                        </a>
                        @endif
                        
                        <div class="mt-3">
                            <h5>Resumo Financeiro</h5>
                            <p><strong>Saldo Devedor:</strong> R$ {{ number_format($venda->saldo_devedor, 2, ',', '.') }}</p>
                            <p><strong>Parcelas Pagas:</strong> {{ $venda->parcelas_pagas }} de {{ $venda->total_parcelas }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Parcelas -->
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Parcelas</h3>
                    </div>
                    <div class="card-body">
                        @if($venda->parcelasVenda->count() > 0)
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Parcela</th>
                                    <th>Valor</th>
                                    <th>Vencimento</th>
                                    <th>Status</th>
                                    <th>Data Pagamento</th>
                                    <th>Valor Pago</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($venda->parcelasVenda->sortBy('numero_parcela') as $parcela)
                                <tr>
                                    <td>{{ $parcela->numero_parcela == 0 ? 'Entrada' : $parcela->numero_parcela }}</td>
                                    <td>R$ {{ number_format($parcela->valor, 2, ',', '.') }}</td>
                                    <td>{{ $parcela->data_vencimento->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge badge-{{ 
                                            $parcela->status == 'pago' ? 'success' : 
                                            ($parcela->data_vencimento < now() ? 'danger' : 'warning') 
                                        }}">
                                            {{ $parcela->status == 'pago' ? 'Pago' : 
                                            ($parcela->data_vencimento < now() ? 'Atrasado' : 'Pendente') }}
                                        </span>
                                    </td>
                                    <td>{{ $parcela->data_pagamento ? $parcela->data_pagamento->format('d/m/Y') : '-' }}</td>
                                    <td>{{ $parcela->valor_pago ? 'R$ ' . number_format($parcela->valor_pago, 2, ',', '.') : '-' }}</td>
                                    <td>
                                        @if(!$parcela->boleto && $parcela->status == 'pendente' && $parcela->numero_parcela > 0)
                                        <form action="{{ route('boletos.gerar', $parcela->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <i class="fas fa-barcode"></i> Gerar Boleto
                                            </button>
                                        </form>
                                        @elseif($parcela->boleto)
                                        <a href="{{ route('boletos.show', $parcela->boleto->id) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i> Ver Boleto
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <p class="text-muted">Nenhuma parcela cadastrada para esta venda.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection