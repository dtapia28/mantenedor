@extends('Bases.base')
@section('contenido')
	<h1>Listado de Prioridades</h1>
	<p>
	</p>
	<form method='HEAD' action="{{ url('priorities/nueva') }}">
	<input type="submit" value="Nueva Prioridad" class="btn btn-primary" name="">
	</form>
	<div class="row mb-6 p-3" style="max-width: 100px text-align: center;">
	    <div class="col-md-2 themed-grid-col"><strong>ID</strong></div>
	    <div class="col-md-4 themed-grid-col"><strong>Nombre</strong></div>
	    <div class="col-md-3 themed-grid-col"><strong>Editar</strong></div>
	    <div class="col-md-3 themed-grid-col"><strong>Eliminar</strong></div>
	</div>
	<div class="row mb-6" style="max-width: 100px text-align: center;">
			@forelse ($priorities as $priority)
				<div class="col-md-2 p-3 themed-grid-col">
					{{ $priority->id }}
				</div>
				<div class="col-md-4 p-3 themed-grid-col">
					<a href="priorities/{{ $priority->id }}">									
						{{ $priority->namePriority }}
					</a>
				</div>
				<div class="col-md-3 p-3 themed-grid-col">									
					<button class="btn btn-info">Editar</button>
				</div>
				<div class="col-md-3 p-3 themed-grid-col">									
					<button class="btn btn-danger">Eliminar</button>
				</div>								
			@empty
				<li>No hay prioridades registradas</li>	
			@endforelse	
	</div>
@endsection	