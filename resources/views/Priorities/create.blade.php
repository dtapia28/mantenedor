@extends('Bases.base')
@section('titulo', "Crear Prioridad")

@section('contenido')
    <h1>Crear Prioridad</h1>

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

    <form method="POST" action="{{ url('priorities/crear') }}">
        {{ csrf_field() }}

        <label for="name">Nombre:</label>
        <input type="text" name="namePriority" id="name" value="{{ old('namePriority') }}">
        <br>
        <button class="btn btn-primary" type="submit">Crear Prioridad</button>
    </form>

    <p>
        <a href="/priorities">Regresar al listado de prioridades</a>
    </p>
@endsection