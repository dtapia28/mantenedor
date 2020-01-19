@extends('layouts.applogin')
@section('titulo', "Reiniciar Contraseña")

@section('content')
    <form method="POST" action="{{ route('password.update') }}" class="md-float-material">
        <div class="text-center">
            <img src="{{ asset('img/logo-blue.png') }}" alt="logo">
        </div>
        <h3 class="text-center txt-primary">Reiniciar contraseña</h3>
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <div class="md-input-wrapper">
            <input id="email" type="email" class="md-form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
            @error('email')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
            <label>Correo electrónico</label>
        </div>
        <div class="md-input-wrapper">
            <input id="password" type="password" class="md-form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                @error('password')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            <label>Contraseña</label>
        </div>
        <div class="md-input-wrapper">
            <input id="password-confirm" type="password" class="md-form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" required autocomplete="new-password">
                @error('password_confirmation')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            <label>Confirmar Contraseña</label>
        </div>
        <div class="row">
            <div class="col-xs-10 offset-xs-1">
                <button type="submit" class="btn btn-primary btn-md btn-block waves-effect text-center m-b-20">REINICIAR</button>
            </div>
        </div>
    </form>
@endsection
