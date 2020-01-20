@extends('layouts.applogin')
@section('titulo', "Reiniciar Contraseña")

@section('content')
@if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
@endif
<form method="POST" action="{{ route('password.email') }}" class="md-float-material">
    <div class="text-center">
        <img src="{{ asset('img/logo-blue.png') }}" alt="logo">
    </div>
    <h3 class="text-center txt-primary">Olvidé mi contraseña</h3>
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
    <div class="row">
        <div class="col-xs-10 offset-xs-1">
            <button type="submit" class="btn btn-primary btn-md btn-block waves-effect text-center m-b-20">REINICIAR</button>
        </div>
    </div>
    <div class="row text-center">
        <a href="{{ route('login') }}" class="text-right f-w-600"><i class="fa fa-arrow-left"></i> Volver al login</a>
    </div>
</form>
@endsection
