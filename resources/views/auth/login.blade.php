@extends('layouts.applogin')

@section('content')
    <form method="POST" action="{{ route('login') }}" id="login-form">
        <h2 class="login-title">Inicio de Sesión</h2>
        @csrf
        <div class="form-group">
            <div class="input-group-icon right">
                <div class="input-icon"><i class="fa fa-envelope"></i></div>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Correo electrónico">
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="form-group">
            <div class="input-group-icon right">
                <div class="input-icon"><i class="fa fa-lock font-16"></i></div>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Contraseña">
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="form-group d-flex justify-content-between">
            <label class="ui-checkbox ui-checkbox-info">
                <input  type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <span class="input-span"></span>Recordarme</label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}">Olvidé mi contraseña</a>
            @endif
        </div>
        <div class="form-group">
            <button class="btn btn-info btn-block" type="submit" style="cursor: pointer">Ingresar <i class="fa fa-sign-in"></i></button>
        </div>
        @if (Route::has('register'))
            <div class="social-auth-hr">
                <span>No estas registrado?</span>
            </div>
            <div class="text-center">
                <a class="color-blue" href="{{ route('register') }}">Crea un cuenta aquí</a>
            </div>
        @endif
    </form>
@endsection
