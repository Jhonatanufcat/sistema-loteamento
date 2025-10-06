<!-- resources/views/auth/login.blade.php -->
@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<p class="login-box-msg">Faça login para acessar o sistema</p>

<form method="POST" action="{{ route('login') }}">
    @csrf
    
    <div class="input-group mb-3">
        <input type="email" class="form-control @error('email') is-invalid @enderror" 
               placeholder="E-mail" name="email" value="{{ old('email') }}" required autofocus>
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-envelope"></span>
            </div>
        </div>
        @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    
    <div class="input-group mb-3">
        <input type="password" class="form-control @error('password') is-invalid @enderror" 
               placeholder="Senha" name="password" required>
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-lock"></span>
            </div>
        </div>
        @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    
    <div class="row">
        <div class="col-8">
            <div class="icheck-primary">
                <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                <label for="remember">
                    Lembrar-me
                </label>
            </div>
        </div>
        
        <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Entrar</button>
        </div>
    </div>
</form>

@if (Route::has('password.request'))
<p class="mb-1">
    <a href="{{ route('password.request') }}">Esqueci minha senha</a>
</p>
@endif
@endsection