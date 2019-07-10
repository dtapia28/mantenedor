@extends('Bases.dashboard')
@section('titulo', "Editar solicitante")
@section('contenido')
    <h1>Editar solicitante</h1>
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
            <form method="POST" action="{{ url("solicitantes/{$solicitante->id}") }}">
                {{ method_field('PUT') }}
                {{ csrf_field() }}

                <label for="name">Nombre:</label>
                <input class="form-control col-md-7" type="text" name="nombreSolicitante" id="nombreSolicitante" value="{{ old('nombreSolicitante', $solicitante->nombreSolicitante) }}">
                <br>              
                <button class="btn btn-primary" type="submit">Actualizar solicitante</button>
            </form>
        </div>
    </div>    
	<p>
		<a href="/solicitantes/">Volver al listado de solicitantes</a>
    </p>
@endsection    