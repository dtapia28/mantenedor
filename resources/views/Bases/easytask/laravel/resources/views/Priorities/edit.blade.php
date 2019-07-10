@extends('Bases.base')

@section('titulo', "Editar prioridad")

@section('contenido')
    <h1>Editar prioridad</h1>
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
            <form method="POST" action="{{ url("priorities/{$priority->id}") }}">
                {{ method_field('PUT') }}
                {{ csrf_field() }}

                <label for="name">Nombre:</label>
                <input class="form-control col-md-7" type="text" name="namePriority" id="namePriority" value="{{ old('namePriority', $priority->namePriority) }}">
                <br>
                <button class="btn btn-primary" type="submit">Actualizar prioridad</button>
            </form>
        </div>
    </div>        
	<p>
		<a href="..">Volver al listado de prioridades</a>
    </p>
@endsection    