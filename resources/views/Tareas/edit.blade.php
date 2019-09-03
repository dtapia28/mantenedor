@extends('Bases.dashboard')
@section('titulo', "Editar tarea ")
@section('contenido')
<h1>Editar tarea {{$tarea->id2}}</h1>
<div class="p-4">
	<div class="form-group col-md-8">
		<form method="POST" action="{{ url("requerimientos/{$requerimiento->id}/tareas/{$tarea->id}") }}">
			{{ method_field('PUT') }}
            {{ csrf_field() }}
            <label for='fechaCierre'>Fecha de Solicitud:</label>
            <input value="{{ $solicitud }}" class="form-control col-md-3" type="date" name="fechaSolicitud">
            <br>              
            <label for='fechaCierre'>Fecha de Cierre:</label>
            <input value="{{ $cierre }}" class="form-control col-md-3" type="date" name="fechaCierre">
            <br> 
            <label for="texto">Tarea:</label>
            <br>
            <textarea class="form-control col-md-7" name="texto" placeholder="Tarea" rows="5" cols="50">{{ $tarea->textoTarea }}</textarea>
            <br>
            <input type="hidden" name="tarea" value={{$tarea->id}}>
            <input type="hidden" name="req" value={{$requerimiento->id}}>        
            <button class="btn btn-primary" type="submit">Actualizar</button>
		</form>
	</div>
</div>
@endsection