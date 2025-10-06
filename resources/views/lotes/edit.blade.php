<!-- resources/views/lotes/edit.blade.php -->
@extends('layouts.app')

@section('title', 'Editar Lote')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Editar Lote</h1>
            </div>
            <div class="col-sm-6">
                <a href="{{ route('lotes.index') }}" class="btn btn-secondary float-right">
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
                <form action="{{ route('lotes.update', $lote->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="numero">Número do Lote *</label>
                                <input type="text" class="form-control @error('numero') is-invalid @enderror" 
                                       id="numero" name="numero" value="{{ old('numero', $lote->numero) }}" required>
                                @error('numero')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="quadra">Quadra *</label>
                                <input type="text" class="form-control @error('quadra') is-invalid @enderror" 
                                       id="quadra" name="quadra" value="{{ old('quadra', $lote->quadra) }}" required>
                                @error('quadra')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="area">Área (m²) *</label>
                                <input type="number" step="0.01" class="form-control @error('area') is-invalid @enderror" 
                                       id="area" name="area" value="{{ old('area', $lote->area) }}" required>
                                @error('area')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="valor">Valor (R$) *</label>
                                <input type="number" step="0.01" class="form-control @error('valor') is-invalid @enderror" 
                                       id="valor" name="valor" value="{{ old('valor', $lote->valor) }}" required>
                                @error('valor')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                            <option value="disponivel" {{ old('status', $lote->status) == 'disponivel' ? 'selected' : '' }}>Disponível</option>
                            <option value="reservado" {{ old('status', $lote->status) == 'reservado' ? 'selected' : '' }}>Reservado</option>
                            <option value="vendido" {{ old('status', $lote->status) == 'vendido' ? 'selected' : '' }}>Vendido</option>
                        </select>
                        @error('status')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="caracteristicas">Características</label>
                        <textarea class="form-control @error('caracteristicas') is-invalid @enderror" 
                                  id="caracteristicas" name="caracteristicas" rows="3">{{ old('caracteristicas', $lote->caracteristicas) }}</textarea>
                        @error('caracteristicas')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="observacoes">Observações</label>
                        <textarea class="form-control @error('observacoes') is-invalid @enderror" 
                                  id="observacoes" name="observacoes" rows="3">{{ old('observacoes', $lote->observacoes) }}</textarea>
                        @error('observacoes')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <!-- resources/views/lotes/edit.blade.php -->
<!-- Adicione ESTES CAMPOS antes do botão de submit -->

<div class="row">
    <div class="col-12">
        <h4>Configurações do Mapa</h4>
        <p class="text-muted">Defina a posição do lote no mapa do loteamento</p>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <label for="pos_x">Posição X</label>
            <input type="number" class="form-control @error('pos_x') is-invalid @enderror" 
                   id="pos_x" name="pos_x" value="{{ old('pos_x', $lote->pos_x ?? 0) }}">
            @error('pos_x')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="pos_y">Posição Y</label>
            <input type="number" class="form-control @error('pos_y') is-invalid @enderror" 
                   id="pos_y" name="pos_y" value="{{ old('pos_y', $lote->pos_y ?? 0) }}">
            @error('pos_y')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="largura">Largura</label>
            <input type="number" class="form-control @error('largura') is-invalid @enderror" 
                   id="largura" name="largura" value="{{ old('largura', $lote->largura ?? 100) }}">
            @error('largura')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="altura">Altura</label>
            <input type="number" class="form-control @error('altura') is-invalid @enderror" 
                   id="altura" name="altura" value="{{ old('altura', $lote->altura ?? 80) }}">
            @error('altura')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>

<!-- Continue com o botão submit... -->
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Atualizar Lote
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection