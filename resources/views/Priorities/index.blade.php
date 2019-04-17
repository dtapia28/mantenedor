@extends('Bases.base')
@section('titulo', "Listado Prioridades")
@section('contenido')
	<h1>Listado de Prioridades</h1>
	<p>
	</p>
	<form method='HEAD' action="{{ url('priorities/nueva') }}">
	<button type="submit" value="Nueva Prioridad" class="btn btn-primary" name="">Nueva Prioridad</button>
	</form>
	<br>
	<table class="table table-striped">
		<thead>
		    <th scope="col"><strong>ID</strong></th>
		    <th scope="col"><strong>Nombre</strong></th>
		    <th scope="col"><strong>Editar</strong></th>
		    <th scope="col"><strong>Eliminar</strong></th>
	    </thead>
		<tbody>
			@forelse ($priorities as $priority)
				<tr>
					<th scope="row">
						{{ $priority->id }}
					</th>
					<td>
						<a href="priorities/{{ $priority->id }}">
							{{ $priority->namePriority }}
						</a>
					</td>
					<td>									
						<form method='HEAD' action="/priorities/{{$priority->id}}/editar">
							<button type="submit" value="Editar" class="btn btn-info" name="">Editar</button>
						</form>
					</td>
					<td>									
					<form method='POST' action="/priorities/{{$priority->id}}">
						{{ csrf_field() }}
						{{ method_field('DELETE') }}						
						<button type="submit" value="Eliminar" class="btn btn-danger" name="">Eliminar</button>
					</form>
					</td>								
			@empty
				<li>No hay prioridades registradas</li>	
			@endforelse									
				</tr>
		</tbody>
		<div class="row mb-6" style="max-width: 100px text-align: center;">

		</div>
	</table>
@endsection	