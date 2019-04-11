@extends('Bases.base')

@section('titulo', "Editar prioridad")

@section('contenido')
    <h1>Editar prioridad</h1>

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

    <form method="POST" action="{{ url("priorities/{$priority->id}") }}">
        {{ method_field('PUT') }}
        {{ csrf_field() }}

        <label for="name">Nombre:</label>
        <input type="text" name="nombreEmpresa" id="nombreEmpresa" value="{{ old('nombreEmpresa', $empresa->nombreEmpresa) }}">
        <br>
        <button type="submit">Actualizar empresa</button>
    </form>

	<p>
		<a href="/empresas/">Volver al listado de Empresas</a>
    </p>