<!-- resources/views/dashboard/index.blade.php -->
@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Dashboard</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Métricas -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $metricas['total_lotes'] }}</h3>
                        <p>Total de Lotes</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-map"></i>
                    </div>
                    <a href="{{ route('lotes.index') }}" class="small-box-footer">
                        Mais info <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $metricas['lotes_vendidos'] }}</h3>
                        <p>Lotes Vendidos</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <a href="{{ route('lotes.index') }}" class="small-box-footer">
                        Mais info <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>R$ {{ number_format($metricas['receita_mes'], 2, ',', '.') }}</h3>
                        <p>Receita do Mês</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <a href="{{ route('financeiro.index') }}" class="small-box-footer">
                        Mais info <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $metricas['atrasados'] }}</h3>
                        <p>Pagamentos Atrasados</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <a href="{{ route('boletos.index') }}" class="small-box-footer">
                        Mais info <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Gráficos e Tabelas -->
        <div class="row">
            <!-- Gráfico de Receitas -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Fluxo de Receitas - {{ now()->year }}</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="graficoReceitas" height="250"></canvas>
                    </div>
                </div>
            </div>

            <!-- Próximos Vencimentos -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Próximos Vencimentos</h3>
                    </div>
                    <div class="card-body p-0">
                        <ul class="products-list product-list-in-card pl-2 pr-2">
                            @foreach($proximosVencimentos as $parcela)
                            <li class="item">
                                <div class="product-info">
                                    <a href="{{ route('vendas.show', $parcela->venda_id) }}" class="product-title">
                                        {{ $parcela->venda->cliente->nome }}
                                        <span class="badge badge-warning float-right">
                                            R$ {{ number_format($parcela->valor, 2, ',', '.') }}
                                        </span>
                                    </a>
                                    <span class="product-description">
                                        Lote {{ $parcela->venda->lote->numero }} - 
                                        Vence: {{ $parcela->data_vencimento->format('d/m/Y') }}
                                    </span>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Vendas Recentes -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Vendas Recentes</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Cliente</th>
                                    <th>Lote</th>
                                    <th>Valor</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($vendasRecentes as $venda)
                                <tr>
                                    <td>{{ $venda->data_venda->format('d/m/Y') }}</td>
                                    <td>{{ $venda->cliente->nome }}</td>
                                    <td>{{ $venda->lote->numero }}</td>
                                    <td>R$ {{ number_format($venda->valor_total, 2, ',', '.') }}</td>
                                    <td>
                                        <span class="badge badge-{{ $venda->status == 'ativa' ? 'success' : 'secondary' }}">
                                            {{ $venda->status }}
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gráfico de Receitas
    var ctx = document.getElementById('graficoReceitas').getContext('2d');
    var grafico = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
            datasets: [{
                label: 'Recebido',
                data: [@foreach($vendasPorMes as $venda) {{ $venda->valor ?? 0 }}, @endforeach],
                backgroundColor: 'rgba(40, 167, 69, 0.8)',
                borderColor: 'rgba(40, 167, 69, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'R$ ' + value.toLocaleString('pt-BR');
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush