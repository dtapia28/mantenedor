@extends('Bases.base')

@section('titulo', "Editar team")

@section('contenido')
    <h1>Editar team</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <h6>Por favor corrige los errores debajo:</h6>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ url("teams/{$team->id}") }}">
        {{ method_field('PUT') }}
        {{ csrf_field() }}

        <label for="name">Nombre:</label>
        <input type="text" name="nameTeam" id="nameTeam" value="{{ old('nameTeam', $team->nameTeam) }}">
        <br>
        <button class="btn btn-primary" type="submit">Actualizar team</button>
    </form>

	<p>
		<a href="/teams/">Volver al listado de Teams</a>
    </p>