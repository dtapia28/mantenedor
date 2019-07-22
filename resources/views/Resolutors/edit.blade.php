@extends('Bases.dashboard')

@section('titulo', "Editar empresa")

@section('contenido')
    <h1>Editar resolutor</h1>
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
            <form method="POST" action="{{ url("resolutors/{$resolutor->id}") }}">
                {{ method_field('PUT') }}
                {{ csrf_field() }}

                <label for="name">Nombre:</label>
                <input class="form-control col-md-6" type="text" name="nombreResolutor" id="nombreResolutor" value="{{ old('nombreResolutor', $resolutor->nombreResolutor) }}">
                <br>
                <label for="idTeam">Team:</label>                
                <select class="form-control col-md-6" name="idTeam">
                    @foreach($teams as $team)
                        <optgroup>
                            <option value="{{ old('idTeam', $team->id) }}">{{ $team->nameTeam }}</option>
                        </optgroup>
                    @endforeach
                </select>
                <br>                
                <button class="btn btn-primary" type="submit">Actualizar resolutor</button>
            </form>
        </div>
    </div>    
	<p>
		<a href="{{url('resolutors')}}">Volver al listado de resolutores</a>
    </p>
@endsection    