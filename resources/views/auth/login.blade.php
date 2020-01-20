@extends('layouts.applogin')
@section('titulo', "Login")

@section('content')
    <form method="POST" action="{{ route('login') }}" class="md-float-material">
        <div class="text-center">
            <img src="{{ asset('img/logo-blue.png') }}" alt="logo">
        </div>
        <h3 class="text-center txt-primary">Ingresa a tu cuenta</h3>
        @csrf
        <div class="md-input-wrapper">
            <input id="email" type="email" class="md-form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                @error('email')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            <label>Correo electrónico</label>
        </div>
        <div class="md-input-wrapper">
            <input id="password" type="password" class="md-form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                @error('password')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            <label>Contraseña</label>
        </div>
        <div class="row">
            <div class="col-sm-6 col-xs-12">
                <div class="rkmd-checkbox checkbox-rotate checkbox-ripple m-b-25">
                    <label class="input-checkbox checkbox-primary">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span class="checkbox"></span>
                    </label>
                    <div class="captions">Recordarme</div>
                </div>
            </div>
            <div class="col-sm-6 col-xs-12 forgot-phone text-right">
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-right f-w-600">Olvidé mi contraseña</a>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-xs-10 offset-xs-1">
                <button type="submit" class="btn btn-primary btn-md btn-block waves-effect text-center m-b-20">INGRESAR</button>
            </div>
        </div>
        @if (Route::has('register'))
            <div class="col-sm-12 col-xs-12 text-center">
                <span class="text-muted">¿No estas registrado?</span>
                <a href="{{ route('register') }}" class="f-w-600 p-l-5">Crea un cuenta aquí</a>
            </div>
        @endif
    </form>
@endsection
