<!-- resources/views/vendas/index.blade.php -->
@extends('layouts.app')

@section('title', 'Gerenciar Vendas')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Gerenciar Vendas</h1>
            </div>
            <div class="col-sm-6">
                <a href="{{ route('vendas.create') }}" class="btn btn-success float-right">
                    <i class="fas fa-plus"></i> Nova Venda
                </a>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <i class="icon fas fa-check"></i> {{ session('success') }}
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <table id="tabelaVendas" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Cliente</th>
                            <th>Lote</th>
                            <th>Valor Total</th>
                            <th>Parcelas</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($vendas as $venda)
                        <tr>
                            <td>{{ $venda->data_venda->format('d/m/Y') }}</td>
                            <td>{{ $venda->cliente->nome }}</td>
                            <td>{{ $venda->lote->numero }}</td>
                            <td>R$ {{ number_format($venda->valor_total, 2, ',', '.') }}</td>
                            <td>{{ $venda->parcelas }}x</td>
                            <td>
                                <span class="badge badge-{{ $venda->status == 'ativa' ? 'success' : ($venda->status == 'quitada' ? 'info' : 'secondary') }}">
                                    {{ $venda->status }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('vendas.show', $venda->id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if(!$venda->contrato)
                                <form action="{{ route('vendas.gerar-contrato', $venda->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fas fa-file-contract"></i>
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#tabelaVendas').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json'
        },
        order: [[0, 'desc']]
    });
});
</script>
@endpush