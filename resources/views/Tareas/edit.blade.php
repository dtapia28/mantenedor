@extends('Bases.dashboard')
@section('titulo', "Editar tarea")
@section('contenido')
<h1>Editar tarea</h1>
<div class="p-4">
	<div class="form-group col-md-8">
		<form method="POST" action="{{ url("requerimientos/{$requerimiento->id}/tareas/{$tarea->id}") }}">
			{{ method_field('PUT') }}
            {{ csrf_field() }}

            
			
		</form>
	</div>
</div>
@endsection