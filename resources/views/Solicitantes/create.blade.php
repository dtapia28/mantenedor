@extends('Bases.base')
@section('titulo', "Crear Solicitante")

@section('contenido')
    <h1>Crear Solicitante</h1>

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

    <form method="POST" action="{{ url('solicitantes/crear') }}">
        {{ csrf_field() }}

        <label for="name">Nombre:</label>
        <input type="text" name="nombreSolicitante" id="name" value="{{ old('nombreSolicitante') }}">
        <br>
        <button class="btn btn-primary" type="submit">Crear Solicitante</button>
    </form>

    <p>
        <a href="/solicitantes">Regresar al listado de solicitantes</a>
    </p>
@endsection