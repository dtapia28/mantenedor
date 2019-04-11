@extends('Bases.base')
@section('titulo', "Crear Empresa")

@section('contenido')
    <h1>Crear Empresa</h1>

    @if ($errors->any())
    <div class="alert alert-danger">
        <h6>Por favor corrige los siguientes errores:</h6>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ url('empresas/crear') }}">
        {{ csrf_field() }}

        <label for="name">Nombre:</label>
        <input type="text" name="nombreEmpresa" id="name" value="{{ old('nombreEmpresa') }}">
        <br>
        <button class="btn btn-primary" type="submit">Crear empresa</button>
    </form>

    <p>
        <a href="/empresas">Regresar al listado de empresas</a>
    </p>
@endsection