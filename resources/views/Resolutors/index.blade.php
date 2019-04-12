@extends('Bases.base')
@section('titulo', 'Resolutores')
@section('contenido')
	<h1>Listado de Resolutores</h1>
	<p>
	</p>
	<form method='HEAD' action="{{ url('resolutors/nuevo') }}">
	<button type="submit" value="Nuevo Resolutor" class="btn btn-primary" name="">Nuevo Resolutor</button>
	</form>
	<tr>
	<table class="table">
		<thead>
		    <th scope="col"><strong>ID</strong></th>
		    <th scope="col"><strong>Nombre</strong></th>
		    <th scope="col"><strong>Editar</strong></th>
		    <th scope="col"><strong>Eliminar</strong></th>
		</thead>
		<tbody>
			@forelse ($resolutors as $resolutor)
				<tr>
				<th scope="row">
					{{ $resolutor->id }}
				</th>
				<th scope="row">
					<a href="/resolutors/{{ $resolutor->id }}">									
						{{ $resolutor->nombreResolutor }}
					</a>
				</th>
				<th scope="row">									
					<form method='HEAD' action="/resolutors/{{$resolutor->id}}/editar">
						{{ csrf_field() }}						
						<button type="submit" value="Editar" class="btn btn-info" name="">Editar</button>
					</form>
				</th>
				<th scope="row">
					<form method='post' action="/resolutors/{{$resolutor->id}}">
						{{ csrf_field() }}
						{{ method_field('DELETE') }}						
						<button type="submit" value="Eliminar" class="btn btn-danger" name="">Eliminar</button>
					</form>
				</th>								
			@empty
				<li>No hay empresas registradas</li>	
			@endforelse
				</tr>
		</tbody>		
	</table>
@endsection