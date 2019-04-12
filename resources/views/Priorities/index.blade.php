@extends('Bases.base')
@section('contenido')
	<h1>Listado de Prioridades</h1>
	<p>
	</p>
	<form method='HEAD' action="{{ url('priorities/nueva') }}">
	<button type="submit" value="Nueva Prioridad" class="btn btn-primary" name="">Nueva Prioridad</button>
	</form>
	<table class="table">
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
					<th scope="row">
						<a href="priorities/{{ $priority->id }}">
							{{ $priority->namePriority }}
						</a>
					</th>
					<th scope="row">									
						<form method='HEAD' action="/priorities/{{$priority->id}}/editar">
							<button type="submit" value="Editar" class="btn btn-info" name="">Editar</button>
						</form>
					</th>
					<th scope="row">									
					<form method='POST' action="/priorities/{{$priority->id}}">
						{{ csrf_field() }}
						{{ method_field('DELETE') }}						
						<button type="submit" value="Eliminar" class="btn btn-danger" name="">Eliminar</button>
					</form>
					</th>								
			@empty
				<li>No hay prioridades registradas</li>	
			@endforelse									
				</tr>
		</tbody>
		<div class="row mb-6" style="max-width: 100px text-align: center;">

		</div>
	</table>
@endsection	