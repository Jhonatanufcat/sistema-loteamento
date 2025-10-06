<!-- resources/views/vendas/create.blade.php -->
@extends('layouts.app')

@section('title', 'Nova Venda')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Nova Venda</h1>
            </div>
            <div class="col-sm-6">
                <a href="{{ route('vendas.index') }}" class="btn btn-secondary float-right">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('vendas.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="lote_id">Lote *</label>
                                <select class="form-control select2 @error('lote_id') is-invalid @enderror" 
                                        id="lote_id" name="lote_id" required>
                                    <option value="">Selecione um lote</option>
                                    @foreach($lotes as $lote)
                                    <option value="{{ $lote->id }}" 
                                            {{ old('lote_id', request('lote_id')) == $lote->id ? 'selected' : '' }}
                                            data-valor="{{ $lote->valor }}">
                                        {{ $lote->numero }} - Quadra {{ $lote->quadra }} - R$ {{ number_format($lote->valor, 2, ',', '.') }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('lote_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="cliente_id">Cliente *</label>
                                <select class="form-control select2 @error('cliente_id') is-invalid @enderror" 
                                        id="cliente_id" name="cliente_id" required>
                                    <option value="">Selecione um cliente</option>
                                    @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}" {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>
                                        {{ $cliente->nome }} - {{ $cliente->cpf_cnpj }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('cliente_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Campo oculto para o user_id -->
                    <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="valor_total">Valor Total (R$) *</label>
                                <input type="number" step="0.01" class="form-control @error('valor_total') is-invalid @enderror" 
                                       id="valor_total" name="valor_total" value="{{ old('valor_total') }}" required>
                                @error('valor_total')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="entrada">Entrada (R$) *</label>
                                <input type="number" step="0.01" class="form-control @error('entrada') is-invalid @enderror" 
                                       id="entrada" name="entrada" value="{{ old('entrada', 0) }}" required>
                                @error('entrada')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="parcelas">Número de Parcelas *</label>
                                <input type="number" class="form-control @error('parcelas') is-invalid @enderror" 
                                       id="parcelas" name="parcelas" value="{{ old('parcelas', 1) }}" min="1" required>
                                @error('parcelas')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="taxa_juros">Taxa de Juros (%)</label>
                                <input type="number" step="0.01" class="form-control @error('taxa_juros') is-invalid @enderror" 
                                       id="taxa_juros" name="taxa_juros" value="{{ old('taxa_juros', 0) }}">
                                @error('taxa_juros')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="data_venda">Data da Venda *</label>
                                <input type="date" class="form-control @error('data_venda') is-invalid @enderror" 
                                       id="data_venda" name="data_venda" value="{{ old('data_venda', date('Y-m-d')) }}" required>
                                @error('data_venda')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="observacoes">Observações</label>
                        <textarea class="form-control @error('observacoes') is-invalid @enderror" 
                                  id="observacoes" name="observacoes" rows="3">{{ old('observacoes') }}</textarea>
                        @error('observacoes')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="alert alert-info">
                        <h5><i class="icon fas fa-info"></i> Resumo do Financiamento</h5>
                        <p id="resumo-financiamento">Selecione um lote para ver o resumo</p>
                    </div>
                    
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Registrar Venda
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.select2').select2();
    
    function atualizarResumo() {
        const valorTotal = parseFloat($('#valor_total').val()) || 0;
        const entrada = parseFloat($('#entrada').val()) || 0;
        const parcelas = parseInt($('#parcelas').val()) || 1;
        const valorFinanciado = valorTotal - entrada;
        const valorParcela = valorFinanciado / parcelas;
        
        if (valorTotal > 0) {
            $('#resumo-financiamento').html(`
                <strong>Valor Total:</strong> R$ ${valorTotal.toFixed(2).replace('.', ',')}<br>
                <strong>Entrada:</strong> R$ ${entrada.toFixed(2).replace('.', ',')}<br>
                <strong>Valor Financiado:</strong> R$ ${valorFinanciado.toFixed(2).replace('.', ',')}<br>
                <strong>Parcelas:</strong> ${parcelas}x de R$ ${valorParcela.toFixed(2).replace('.', ',')}
            `);
        }
    }
    
    $('#lote_id').change(function() {
        const selectedOption = $(this).find('option:selected');
        const valorLote = selectedOption.data('valor');
        if (valorLote) {
            $('#valor_total').val(valorLote);
            atualizarResumo();
        }
    });
    
    $('#valor_total, #entrada, #parcelas').on('input', atualizarResumo);
    
    // Inicializar resumo
    atualizarResumo();
});
</script>
@endpush