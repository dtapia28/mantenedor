@extends('Bases.base')

@section('titulo', "Editar Team")

@section('contenido')
    <h1>Editar team</h1>
    <br>
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
    <div class="form-row align-items-center">
        <div class="form-group col-md-8">  
            <form method="POST" action="{{ url("teams/{$team->id}") }}">
                {{ method_field('PUT') }}
                {{ csrf_field() }}

                <label for="name">Nombre:</label>
                <input class="form-control col-md-7" type="text" name="nameTeam" id="nameTeam" value="{{ old('nameTeam', $team->nameTeam) }}">
                <br>
                <button class="btn btn-primary" type="submit">Actualizar team</button>
            </form>
        </div>
    </div>    

	<p>
		<a href="/teams/">Volver al listado de Teams</a>
    </p>
@endsection    