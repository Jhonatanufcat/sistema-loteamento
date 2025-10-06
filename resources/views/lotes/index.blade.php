<!-- resources/views/lotes/index.blade.php -->
@extends('layouts.app')

@section('title', 'Gerenciar Lotes')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Gerenciar Lotes</h1>
            </div>
            <div class="col-sm-6">
                <a href="{{ route('lotes.create') }}" class="btn btn-success float-right">
                    <i class="fas fa-plus"></i> Novo Lote
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

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <i class="icon fas fa-ban"></i> {{ session('error') }}
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <table id="tabelaLotes" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Número</th>
                            <th>Quadra</th>
                            <th>Área (m²)</th>
                            <th>Valor</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lotes as $lote)
                        <tr>
                            <td>{{ $lote->numero }}</td>
                            <td>{{ $lote->quadra }}</td>
                            <td>{{ number_format($lote->area, 2, ',', '.') }}</td>
                            <td>R$ {{ number_format($lote->valor, 2, ',', '.') }}</td>
                            <td>
                                <span class="badge badge-{{ $lote->status == 'disponivel' ? 'success' : ($lote->status == 'reservado' ? 'warning' : 'danger') }}">
                                    {{ $lote->status_formatado }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('lotes.show', $lote->id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('lotes.edit', $lote->id) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('lotes.destroy', $lote->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" 
                                            onclick="return confirm('Tem certeza que deseja excluir este lote?')">
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
    $('#tabelaLotes').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json'
        }
    });
});
</script>
@endpush