<!-- resources/views/boletos/index.blade.php -->
@extends('layouts.app')

@section('title', 'Gerenciar Boletos')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Gerenciar Boletos</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <table id="tabelaBoletos" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Nosso Número</th>
                            <th>Cliente</th>
                            <th>Vencimento</th>
                            <th>Valor</th>
                            <th>Situação</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($boletos as $boleto)
                        <tr>
                            <td>{{ $boleto->nosso_numero }}</td>
                            <td>{{ $boleto->pagador_nome }}</td>
                            <td>{{ $boleto->data_vencimento->format('d/m/Y') }}</td>
                            <td>R$ {{ number_format($boleto->valor, 2, ',', '.') }}</td>
                            <td>
                                <span class="badge badge-{{ 
                                    $boleto->situacao == 'ABERTO' ? 'warning' : 
                                    ($boleto->situacao == 'LIQUIDADO' ? 'success' : 
                                    ($boleto->situacao == 'VENCIDO' ? 'danger' : 'secondary')) 
                                }}">
                                    {{ $boleto->situacao_formatada }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('boletos.show', $boleto->id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('boletos.segunda-via', $boleto->id) }}" class="btn btn-primary btn-sm" target="_blank">
                                    <i class="fas fa-print"></i>
                                </a>
                                <form action="{{ route('boletos.consultar', $boleto->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-warning btn-sm">
                                        <i class="fas fa-sync"></i>
                                    </button>
                                </form>
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
    $('#tabelaBoletos').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json'
        },
        order: [[2, 'desc']]
    });
});
</script>
@endpush