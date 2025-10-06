<!-- resources/views/contratos/index.blade.php -->
@extends('layouts.app')

@section('title', 'Gerenciar Contratos')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Gerenciar Contratos</h1>
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
                <table id="tabelaContratos" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Número</th>
                            <th>Cliente</th>
                            <th>Lote</th>
                            <th>Data Emissão</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($contratos as $contrato)
                        <tr>
                            <td>{{ $contrato->numero_contrato }}</td>
                            <td>{{ $contrato->venda->cliente->nome }}</td>
                            <td>{{ $contrato->venda->lote->numero }}</td>
                            <td>{{ $contrato->data_emissao->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge badge-{{ 
                                    $contrato->status == 'assinado' ? 'success' : 
                                    ($contrato->status == 'rascunho' ? 'warning' : 'secondary') 
                                }}">
                                    {{ $contrato->status_formatado }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('contratos.show', $contrato->id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('contratos.download', $contrato->id) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-download"></i>
                                </a>
                                <form action="{{ route('contratos.destroy', $contrato->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" 
                                            onclick="return confirm('Tem certeza que deseja excluir este contrato?')">
                                        <i class="fas fa-trash"></i>
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
    $('#tabelaContratos').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json'
        },
        order: [[3, 'desc']]
    });
});
</script>
@endpush