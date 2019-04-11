@extends('Bases.base')
@section('contenido')
	<h1>Listado de Resolutores</h1>
	<p>
	</p>
	<form method='HEAD' action="{{ url('resolutors/nuevo') }}">
	<input type="submit" value="Nuevo Resolutor" class="btn btn-primary" name="">
	</form>
	<div class="row mb-6 p-3" style="max-width: 100px text-align: center;">
	    <div class="col-md-2 themed-grid-col"><strong>ID</strong></div>
	    <div class="col-md-4 themed-grid-col"><strong>Nombre</strong></div>
	    <div class="col-md-3 themed-grid-col"><strong>Editar</strong></div>
	    <div class="col-md-3 themed-grid-col"><strong>Eliminar</strong></div>
	</div>
	<div class="row mb-6" style="max-width: 100px text-align: center;">
			@forelse ($resolutors as $resolutor)
				<div class="col-md-2 p-3 themed-grid-col">
					{{ $resolutor->id }}
				</div>
				<div class="col-md-4 p-3 themed-grid-col">
					<a href="resolutors"></a>									
					{{ $resolutor->nombreResolutor }}
				</div>
				<div class="col-md-3 p-3 themed-grid-col">									
					<form method='HEAD' action="/resolutors/{{$resolutor->id}}/editar">
						<input type="submit" value="Editar" class="btn btn-info" name="">
					</form>
				</div>
				<div class="col-md-3 p-3 themed-grid-col">									
					<button class="btn btn-danger">Eliminar</button>
				</div>								
			@empty
				<li>No hay empresas registradas</li>	
			@endforelse	
	</div>
@endsection	