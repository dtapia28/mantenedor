@extends('Bases.base')
@section('titulo', 'Solicitantes')
@section('contenido')
	<h1>Listado de Solicitantes</h1>
	<p>
	</p>
	<form method='HEAD' action="{{ url('solicitantes/nuevo') }}">
	<button type="submit" value="Nuevo Solicitante" class="btn btn-primary" name="">Nuevo Solicitante</button>
	</form>
	<br>
	<tr>
	<table class="table table-striped">
		<thead>
		    <th scope="col"><strong>ID</strong></th>
		    <th scope="col"><strong>Nombre</strong></th>
		    <th scope="col"><strong>Editar</strong></th>
		    <th scope="col"><strong>Eliminar</strong></th>
		</thead>
		<tbody>
			@forelse ($solicitantes as $solicitante)
				<tr>
				<th scope="row">
					{{ $solicitante->id }}
				</th>
				<th scope="row">
					<a href="/solicitantes/{{ $solicitante->id }}">									
						{{ $solicitante->nombreSolicitante }}
					</a>
				</th>
				<th scope="row">									
					<form method='HEAD' action="/solicitantes/{{$solicitante->id}}/editar">
						{{ csrf_field() }}						
						<button type="submit" value="Editar" class="btn btn-info" name="">Editar</button>
					</form>
				</th>
				<th scope="row">
					<form method='post' action="/solicitantes/{{$solicitante->id}}">
						{{ csrf_field() }}
						{{ method_field('DELETE') }}						
						<button type="submit" value="Eliminar" class="btn btn-danger" name="">Eliminar</button>
					</form>
				</th>								
			@empty
				<li>No hay solicitantes registrados</li>	
			@endforelse
				</tr>
		</tbody>		
	</table>
@endsection