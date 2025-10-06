<!-- resources/views/boletos/show.blade.php -->
@extends('layouts.app')

@section('title', 'Detalhes do Boleto')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Detalhes do Boleto</h1>
            </div>
            <div class="col-sm-6">
                <a href="{{ route('boletos.index') }}" class="btn btn-secondary float-right">
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
                        <h3 class="card-title">Informações do Boleto</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th>Nosso Número:</th>
                                <td>{{ $boleto->nosso_numero }}</td>
                            </tr>
                            <tr>
                                <th>Cliente:</th>
                                <td>{{ $boleto->pagador_nome }}</td>
                            </tr>
                            <tr>
                                <th>CPF/CNPJ:</th>
                                <td>{{ $boleto->pagador_cpf_cnpj }}</td>
                            </tr>
                            <tr>
                                <th>Valor:</th>
                                <td>R$ {{ number_format($boleto->valor, 2, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th>Vencimento:</th>
                                <td>{{ $boleto->data_vencimento->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <th>Emissão:</th>
                                <td>{{ $boleto->data_emissao->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <th>Situação:</th>
                                <td>
                                    <span class="badge badge-{{ 
                                        $boleto->situacao == 'ABERTO' ? 'warning' : 
                                        ($boleto->situacao == 'LIQUIDADO' ? 'success' : 
                                        ($boleto->situacao == 'VENCIDO' ? 'danger' : 'secondary')) 
                                    }}">
                                        {{ $boleto->situacao_formatada }}
                                    </span>
                                </td>
                            </tr>
                            @if($boleto->data_pagamento)
                            <tr>
                                <th>Data Pagamento:</th>
                                <td>{{ $boleto->data_pagamento->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <th>Valor Pago:</th>
                                <td>R$ {{ number_format($boleto->valor_pago, 2, ',', '.') }}</td>
                            </tr>
                            @endif
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
                        <div class="btn-group-vertical w-100">
                            @if($boleto->url_boleto)
                            <a href="{{ $boleto->url_boleto }}" target="_blank" class="btn btn-primary mb-2">
                                <i class="fas fa-external-link-alt"></i> Visualizar Boleto
                            </a>
                            @endif
                            
                            <a href="{{ route('boletos.segunda-via', $boleto->id) }}" target="_blank" class="btn btn-info mb-2">
                                <i class="fas fa-print"></i> Segunda Via
                            </a>
                            
                            <form action="{{ route('boletos.consultar', $boleto->id) }}" method="POST" class="mb-2">
                                @csrf
                                <button type="submit" class="btn btn-warning w-100">
                                    <i class="fas fa-sync"></i> Atualizar Situação
                                </button>
                            </form>
                            
                            @if($boleto->situacao == 'ABERTO')
                            <form action="{{ route('boletos.baixar', $boleto->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger w-100" 
                                        onclick="return confirm('Tem certeza que deseja baixar este boleto?')">
                                    <i class="fas fa-ban"></i> Baixar Boleto
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
                
                @if($boleto->venda)
                <div class="card mt-3">
                    <div class="card-header">
                        <h3 class="card-title">Informações da Venda</h3>
                    </div>
                    <div class="card-body">
                        <p><strong>Lote:</strong> {{ $boleto->venda->lote->numero }}</p>
                        <p><strong>Quadra:</strong> {{ $boleto->venda->lote->quadra }}</p>
                        <p><strong>Parcela:</strong> {{ $boleto->parcela->numero_parcela ?? 'N/A' }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection