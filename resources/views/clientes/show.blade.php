<!-- resources/views/clientes/show.blade.php -->
@extends('layouts.app')

@section('title', 'Detalhes do Cliente')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Detalhes do Cliente</h1>
            </div>
            <div class="col-sm-6">
                <a href="{{ route('clientes.index') }}" class="btn btn-secondary float-right">
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
                        <h3 class="card-title">Informações Pessoais</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th>Nome:</th>
                                <td>{{ $cliente->nome }}</td>
                            </tr>
                            <tr>
                                <th>CPF/CNPJ:</th>
                                <td>{{ $cliente->cpf_cnpj }}</td>
                            </tr>
                            <tr>
                                <th>E-mail:</th>
                                <td>{{ $cliente->email ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Telefone:</th>
                                <td>{{ $cliente->telefone ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Celular:</th>
                                <td>{{ $cliente->celular ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Endereço</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th>Endereço:</th>
                                <td>{{ $cliente->endereco ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>CEP:</th>
                                <td>{{ $cliente->cep ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Cidade:</th>
                                <td>{{ $cliente->cidade ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>UF:</th>
                                <td>{{ $cliente->uf ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Vendas do Cliente -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Vendas Realizadas</h3>
                    </div>
                    <div class="card-body">
                        @if($vendas->count() > 0)
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Lote</th>
                                    <th>Valor Total</th>
                                    <th>Status</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($vendas as $venda)
                                <tr>
                                    <td>{{ $venda->data_venda->format('d/m/Y') }}</td>
                                    <td>{{ $venda->lote->numero }}</td>
                                    <td>R$ {{ number_format($venda->valor_total, 2, ',', '.') }}</td>
                                    <td>
                                        <span class="badge badge-{{ $venda->status == 'ativa' ? 'success' : 'secondary' }}">
                                            {{ $venda->status }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('vendas.show', $venda->id) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <p class="text-muted">Nenhuma venda realizada por este cliente.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <a href="{{ route('clientes.edit', $cliente->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Editar Cliente
                </a>
            </div>
        </div>
    </div>
</section>
@endsection