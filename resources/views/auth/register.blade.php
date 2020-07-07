@extends('layouts.applogin')
@section('titulo', "Crear Cuenta")
<?php
$nombre = Illuminate\Support\Facades\Cache::get('nombre');
$apellido = Illuminate\Support\Facades\Cache::get('apellido');
$mail = Illuminate\Support\Facades\Cache::get('mail');

?>

@section('content')
<form method="POST" action="{{ route('register') }}" class="md-float-material">
    <div class="text-center">
        <img src="{{ asset('img/logo-blue.png') }}" alt="logo">
    </div>
    <h4 class="text-center txt-primary"> Crear cuenta</h4>
    @csrf
    <div class="md-input-wrapper">
        <input id="name" type="text" class="md-form-control @error('name') is-invalid @enderror" name="name" value="{{$nombre}}" required autocomplete="name" autofocus>
        @error('name')
            <span class="text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
        <label>Nombre</label>
    </div>
    <div class="md-input-wrapper">
        <input id="lastname" type="text" class="md-form-control @error('name') is-invalid @enderror" name="lastname" value="{{$apellido}}" required autocomplete="name" autofocus>
        @error('name')
            <span class="text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
        <label>Apellido</label>
    </div>    
    <div class="md-input-wrapper">
        <input id="email" type="email" class="md-form-control @error('email') is-invalid @enderror" name="email" value="{{$mail}}" required autocomplete="email">
        @error('email')
            <span class="text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
        <label>Correo electrónico</label>
    </div>
    <div class="md-input-wrapper">
        <input id="rut" type="text" class="md-form-control @error('rut') no es valido @enderror" name="rut" value="{{ old('rut') }}" required maxlength="12" max="12">
        <label>Rut Empresa</label>
    </div>
    <div class="md-input-wrapper">
        <input id="nombre" type="text" class="md-form-control @error('nombre') no es valido @enderror" name="nombre" value="{{ old('nombre') }}">
        <label>Nombre Empresa</label>
    </div>
    <div class="md-input-wrapper">
        <input id="n_telefono" type="text" title="Si es que desea que el sistema le pueda enviar mensajes via WhatsApp." class="md-form-control @error('n_telefono') no es valido @enderror" name="n_telefono" value="{{ old('n_telefono') }}">
        <label>Número Teléfono</label>
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
        <label>Confirmar Contraseña</label>
    </div>
    <div class="row">
        <div class="col-xs-10 offset-xs-1">
            <button type="submit" class="btn btn-primary btn-md btn-block waves-effect text-center m-b-20">REGISTRAR</button>
        </div>
    </div>
    <div class="row text-center">
        <a href="{{ route('login') }}" class="text-right f-w-600"><i class="fa fa-arrow-left"></i> Volver al login</a>
    </div>
</form>
@endsection

@section('js')
    <script src="{{ asset('js/jquery.mask.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        $(function() {
            $('#rut').mask('00000000-A', {reverse: true, 'translation': {A: {pattern: /[0-9Kk]/}}});
        })
    </script>
@endsection
