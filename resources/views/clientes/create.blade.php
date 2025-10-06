<!-- resources/views/clientes/create.blade.php -->
@extends('layouts.app')

@section('title', 'Cadastrar Cliente')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Cadastrar Novo Cliente</h1>
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
        <div class="card">
            <div class="card-body">
                <form action="{{ route('clientes.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nome">Nome Completo *</label>
                                <input type="text" class="form-control @error('nome') is-invalid @enderror" 
                                       id="nome" name="nome" value="{{ old('nome') }}" required>
                                @error('nome')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="cpf_cnpj">CPF/CNPJ *</label>
                                <input type="text" class="form-control @error('cpf_cnpj') is-invalid @enderror" 
                                       id="cpf_cnpj" name="cpf_cnpj" value="{{ old('cpf_cnpj') }}" required>
                                @error('cpf_cnpj')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">E-mail</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}">
                                @error('email')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="telefone">Telefone</label>
                                <input type="text" class="form-control @error('telefone') is-invalid @enderror" 
                                       id="telefone" name="telefone" value="{{ old('telefone') }}">
                                @error('telefone')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="celular">Celular</label>
                                <input type="text" class="form-control @error('celular') is-invalid @enderror" 
                                       id="celular" name="celular" value="{{ old('celular') }}">
                                @error('celular')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="endereco">Endereço</label>
                        <input type="text" class="form-control @error('endereco') is-invalid @enderror" 
                               id="endereco" name="endereco" value="{{ old('endereco') }}">
                        @error('endereco')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="cep">CEP</label>
                                <input type="text" class="form-control @error('cep') is-invalid @enderror" 
                                       id="cep" name="cep" value="{{ old('cep') }}">
                                @error('cep')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="cidade">Cidade</label>
                                <input type="text" class="form-control @error('cidade') is-invalid @enderror" 
                                       id="cidade" name="cidade" value="{{ old('cidade') }}">
                                @error('cidade')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="uf">UF</label>
                                <select class="form-control @error('uf') is-invalid @enderror" id="uf" name="uf">
                                    <option value="">Selecione</option>
                                    <option value="AC" {{ old('uf') == 'AC' ? 'selected' : '' }}>AC</option>
                                    <option value="AL" {{ old('uf') == 'AL' ? 'selected' : '' }}>AL</option>
                                    <option value="AP" {{ old('uf') == 'AP' ? 'selected' : '' }}>AP</option>
                                    <option value="AM" {{ old('uf') == 'AM' ? 'selected' : '' }}>AM</option>
                                    <option value="BA" {{ old('uf') == 'BA' ? 'selected' : '' }}>BA</option>
                                    <option value="CE" {{ old('uf') == 'CE' ? 'selected' : '' }}>CE</option>
                                    <option value="DF" {{ old('uf') == 'DF' ? 'selected' : '' }}>DF</option>
                                    <option value="ES" {{ old('uf') == 'ES' ? 'selected' : '' }}>ES</option>
                                    <option value="GO" {{ old('uf') == 'GO' ? 'selected' : '' }}>GO</option>
                                    <option value="MA" {{ old('uf') == 'MA' ? 'selected' : '' }}>MA</option>
                                    <option value="MT" {{ old('uf') == 'MT' ? 'selected' : '' }}>MT</option>
                                    <option value="MS" {{ old('uf') == 'MS' ? 'selected' : '' }}>MS</option>
                                    <option value="MG" {{ old('uf') == 'MG' ? 'selected' : '' }}>MG</option>
                                    <option value="PA" {{ old('uf') == 'PA' ? 'selected' : '' }}>PA</option>
                                    <option value="PB" {{ old('uf') == 'PB' ? 'selected' : '' }}>PB</option>
                                    <option value="PR" {{ old('uf') == 'PR' ? 'selected' : '' }}>PR</option>
                                    <option value="PE" {{ old('uf') == 'PE' ? 'selected' : '' }}>PE</option>
                                    <option value="PI" {{ old('uf') == 'PI' ? 'selected' : '' }}>PI</option>
                                    <option value="RJ" {{ old('uf') == 'RJ' ? 'selected' : '' }}>RJ</option>
                                    <option value="RN" {{ old('uf') == 'RN' ? 'selected' : '' }}>RN</option>
                                    <option value="RS" {{ old('uf') == 'RS' ? 'selected' : '' }}>RS</option>
                                    <option value="RO" {{ old('uf') == 'RO' ? 'selected' : '' }}>RO</option>
                                    <option value="RR" {{ old('uf') == 'RR' ? 'selected' : '' }}>RR</option>
                                    <option value="SC" {{ old('uf') == 'SC' ? 'selected' : '' }}>SC</option>
                                    <option value="SP" {{ old('uf') == 'SP' ? 'selected' : '' }}>SP</option>
                                    <option value="SE" {{ old('uf') == 'SE' ? 'selected' : '' }}>SE</option>
                                    <option value="TO" {{ old('uf') == 'TO' ? 'selected' : '' }}>TO</option>
                                </select>
                                @error('uf')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Cadastrar Cliente
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection