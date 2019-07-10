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
		    <th scope="col"><strong></strong></th>
		    <th scope="col"><strong></strong></th>
	    </thead>
		<tbody>
			@forelse ($priorities as $priority)
				<tr>
					<th scope="row">
						{{ $priority->id }}
					</th>
					<td>
						<a href="../public/priorities/{{ $priority->id }}">
							{{ $priority->namePriority }}
						</a>
					</td>
					<td>									
						<form method='HEAD' action="priorities/{{$priority->id}}/editar">
							<input style="text-align: center;" type="image" align="center" src="{{ asset('img/edit.png') }}" width="30" height="30">
						</form>
					</td>
					<td>									
					<form method='POST' action="priorities/{{$priority->id}}">
						{{ csrf_field() }}
						{{ method_field('DELETE') }}						
							<input type="image" align="center" src="{{ asset('img/delete.png') }}" width="30" height="30">
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
	{{ $priorities->links() }}	
@endsection	