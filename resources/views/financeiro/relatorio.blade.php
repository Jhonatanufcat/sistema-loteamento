<!-- resources/views/financeiro/relatorio.blade.php -->
@extends('layouts.app')

@section('title', 'Relatório Financeiro')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Relatório Financeiro</h1>
            </div>
            <div class="col-sm-6">
                <a href="{{ route('financeiro.index') }}" class="btn btn-secondary float-right">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Filtrar Relatório</h3>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('financeiro.relatorio') }}">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="data_inicio">Data Início</label>
                                <input type="date" class="form-control" id="data_inicio" name="data_inicio" 
                                       value="{{ $dataInicio }}">
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="data_fim">Data Fim</label>
                                <input type="date" class="form-control" id="data_fim" name="data_fim" 
                                       value="{{ $dataFim }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-filter"></i> Filtrar
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Parcelas no Período</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Vencimento</th>
                            <th>Cliente</th>
                            <th>Lote</th>
                            <th>Parcela</th>
                            <th>Valor</th>
                            <th>Status</th>
                            <th>Data Pagamento</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($parcelas as $parcela)
                        <tr>
                            <td>{{ $parcela->data_vencimento->format('d/m/Y') }}</td>
                            <td>{{ $parcela->venda->cliente->nome }}</td>
                            <td>{{ $parcela->venda->lote->numero }}</td>
                            <td>{{ $parcela->numero_parcela == 0 ? 'Entrada' : 'Parcela ' . $parcela->numero_parcela }}</td>
                            <td>R$ {{ number_format($parcela->valor, 2, ',', '.') }}</td>
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
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4"><strong>Total</strong></td>
                            <td><strong>R$ {{ number_format($parcelas->sum('valor'), 2, ',', '.') }}</strong></td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection