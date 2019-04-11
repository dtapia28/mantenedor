@extends('Bases.base')
@section('titulo', "Crear Team")

@section('contenido')
    <h1>Crear Team</h1>

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

    <form method="POST" action="{{ url('teams/crear') }}">
        {{ csrf_field() }}

        <label for="name">Nombre:</label>
        <input type="text" name="nameTeam" id="name" value="{{ old('nameTeam') }}">
        <br>
        <button class="btn btn-primary" type="submit">Crear team</button>
    </form>

    <p>
        <a href="/teams">Regresar al listado de teams</a>
    </p>
@endsection