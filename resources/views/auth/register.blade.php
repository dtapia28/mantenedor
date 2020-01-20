@extends('layouts.applogin')
@section('titulo', "Crear Cuenta")

@section('content')
<<<<<<< HEAD
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Registro') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Nombre') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="lastname" class="col-md-4 col-form-label text-md-right">{{ __('Apellido') }}</label>

                            <div class="col-md-6">
                                <input id="lastname" type="text" class="form-control @error('lastname') is-invalid @enderror" name="lastname" value="{{ old('lastname') }}" required autocomplete="lastname" autofocus>

                                @error('lastname')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>                        

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="rut" class="col-md-4 col-form-label text-md-right">{{ __('Rut Empresa') }}</label>
                            <div class="col-md-6">
                            <input placeholder="11111111-1" maxlength="12" max="12" id="rut" type="text" class="form-control @error('rut') no es valido @enderror" name="rut" value="{{ old('rut') }}">
                            </div>                       
                        </div>
                        <div class="form-group row">
                            <label for="nombre" class="col-md-4 col-form-label text-md-right">{{ __('Nombre Empresa') }}</label>
                            <div class="col-md-6">
                            <input id="nombre" type="text" class="form-control @error('nombre') no es valido @enderror" name="nombre" value="{{ old('nombre') }}">
                            </div>                       
                        </div>                        

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirmar Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Registrar') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
=======
<form method="POST" action="{{ route('register') }}" class="md-float-material">
    <div class="text-center">
        <img src="{{ asset('img/logo-blue.png') }}" alt="logo">
    </div>
    <h4 class="text-center txt-primary"> Crear cuenta</h4>
    @csrf
    <div class="md-input-wrapper">
        <input id="name" type="text" class="md-form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
        @error('name')
            <span class="text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
        <label>Nombre</label>
    </div>
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
        <input id="rut" type="text" class="md-form-control @error('rut') no es valido @enderror" name="rut" value="{{ old('rut') }}" required maxlength="12" max="12">
        <label>Rut Empresa</label>
    </div>
    <div class="md-input-wrapper">
        <input id="nombre" type="text" class="md-form-control @error('nombre') no es valido @enderror" name="nombre" value="{{ old('nombre') }}">
        <label>Nombre Empresa</label>
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
>>>>>>> frontend
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
