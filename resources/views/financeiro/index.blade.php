<!-- resources/views/financeiro/index.blade.php -->
@extends('layouts.app')

@section('title', 'Financeiro')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Dashboard Financeiro</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Métricas Financeiras -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>R$ {{ number_format($resumo['recebido_mes'], 2, ',', '.') }}</h3>
                        <p>Recebido Este Mês</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>R$ {{ number_format($resumo['a_receber_mes'], 2, ',', '.') }}</h3>
                        <p>A Receber Este Mês</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-calendar"></i>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>R$ {{ number_format($resumo['atrasados'], 2, ',', '.') }}</h3>
                        <p>Pagamentos Atrasados</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>R$ {{ number_format($resumo['total_vendas'], 2, ',', '.') }}</h3>
                        <p>Total em Vendas Ativas</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fluxo de Caixa -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Fluxo de Caixa - Próximos 30 Dias</h3>
                        <div class="card-tools">
                            <a href="{{ route('financeiro.relatorio') }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-download"></i> Relatório Completo
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Data Vencimento</th>
                                    <th>Cliente</th>
                                    <th>Descrição</th>
                                    <th>Valor</th>
                                    <th>Situação</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($fluxoCaixa as $parcela)
                                <tr>
                                    <td>{{ $parcela->data_vencimento->format('d/m/Y') }}</td>
                                    <td>{{ $parcela->venda->cliente->nome }}</td>
                                    <td>
                                        Parcela {{ $parcela->numero_parcela }} - 
                                        Lote {{ $parcela->venda->lote->numero }}
                                    </td>
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
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection