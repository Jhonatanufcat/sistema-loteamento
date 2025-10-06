<!-- resources/views/contratos/show.blade.php -->
@extends('layouts.app')

@section('title', 'Detalhes do Contrato')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Detalhes do Contrato</h1>
            </div>
            <div class="col-sm-6">
                <a href="{{ route('contratos.index') }}" class="btn btn-secondary float-right">
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
                        <h3 class="card-title">Informações do Contrato</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th>Número:</th>
                                <td>{{ $contrato->numero_contrato }}</td>
                            </tr>
                            <tr>
                                <th>Cliente:</th>
                                <td>{{ $contrato->venda->cliente->nome }}</td>
                            </tr>
                            <tr>
                                <th>CPF/CNPJ:</th>
                                <td>{{ $contrato->venda->cliente->cpf_cnpj }}</td>
                            </tr>
                            <tr>
                                <th>Lote:</th>
                                <td>{{ $contrato->venda->lote->numero }} - Quadra {{ $contrato->venda->lote->quadra }}</td>
                            </tr>
                            <tr>
                                <th>Valor Total:</th>
                                <td>R$ {{ number_format($contrato->venda->valor_total, 2, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th>Data Emissão:</th>
                                <td>{{ $contrato->data_emissao->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td>
                                    <span class="badge badge-{{ 
                                        $contrato->status == 'assinado' ? 'success' : 
                                        ($contrato->status == 'rascunho' ? 'warning' : 'secondary') 
                                    }}">
                                        {{ $contrato->status_formatado }}
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
                        <div class="btn-group-vertical w-100">
                            <a href="{{ route('contratos.download', $contrato->id) }}" class="btn btn-primary mb-2">
                                <i class="fas fa-download"></i> Download PDF
                            </a>
                            
                            @if($contrato->status == 'rascunho')
                            <form action="{{ route('contratos.update', $contrato->id) }}" method="POST" class="mb-2">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="assinado">
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="fas fa-check"></i> Marcar como Assinado
                                </button>
                            </form>
                            @endif
                            
                            <form action="{{ route('contratos.destroy', $contrato->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100" 
                                        onclick="return confirm('Tem certeza que deseja excluir este contrato?')">
                                    <i class="fas fa-trash"></i> Excluir Contrato
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Conteúdo do Contrato -->
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Conteúdo do Contrato</h3>
                    </div>
                    <div class="card-body">
                        <div style="white-space: pre-line; line-height: 1.6;">
                            {{ $contrato->conteudo }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection