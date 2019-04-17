@extends('Bases.base')
@section('titulo', 'Resolutores')
@section('contenido')
	<h1>Listado de Resolutores</h1>
	<p>
	</p>
	<form method='HEAD' action="{{ url('resolutors/nuevo') }}">
	<button type="submit" value="Nuevo Resolutor" class="btn btn-primary" name="">Nuevo Resolutor</button>
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
			@forelse ($resolutors as $resolutor)
				<tr>
				<th scope="row">
					{{ $resolutor->id }}
				</th>
				<td>
					<a href="/resolutors/{{ $resolutor->id }}">									
						{{ $resolutor->nombreResolutor }}
					</a>
				</td>
				<td>									
					<form method='HEAD' action="/resolutors/{{$resolutor->id}}/editar">
						{{ csrf_field() }}						
						<button type="submit" value="Editar" class="btn btn-info" name="">Editar</button>
					</form>
				</td>
				<td>
					<form method='post' action="/resolutors/{{$resolutor->id}}">
						{{ csrf_field() }}
						{{ method_field('DELETE') }}						
						<button type="submit" value="Eliminar" class="btn btn-danger" name="">Eliminar</button>
					</form>
				</td>								
			@empty
				<li>No hay empresas registradas</li>	
			@endforelse
				</tr>
		</tbody>		
	</table>
@endsection