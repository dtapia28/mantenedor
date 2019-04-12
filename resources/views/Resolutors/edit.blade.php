@extends('Bases.base')

@section('titulo', "Editar empresa")

@section('contenido')
    <h1>Editar resolutor</h1>

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

    <form method="POST" action="{{ url("resolutors/{$resolutor->id}") }}">
        {{ method_field('PUT') }}
        {{ csrf_field() }}

        <label for="name">Nombre:</label>
        <input type="text" name="nombreResolutor" id="nombreResolutor" value="{{ old('nombreResolutor', $resolutor->nombreResolutor) }}">
        <br>
        <br>
        <select name="idEmpresa">
            @foreach($empresas as $empresa)
                <optgroup>
                    <option value="{{ old('idEmpresa', $empresa->id) }}">{{ $empresa->nombreEmpresa }}</option>
                </optgroup>
            @endforeach
        </select>
        <br>
        <select name="idTeam">
            @foreach($teams as $team)
                <optgroup>
                    <option value="{{ old('idTeam', $team->id) }}">{{ $team->nameTeam }}</option>
                </optgroup>
            @endforeach
        </select>
        <br>                
        <button class="btn btn-primary" type="submit">Actualizar resolutor</button>
    </form>

	<p>
		<a href="/resolutors/">Volver al listado de resolutores</a>
    </p>