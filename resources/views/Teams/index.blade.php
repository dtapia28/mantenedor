@extends('Bases.base')
@section('titulo', 'Teams')
@section('contenido')
	<h1>Listado de Teams</h1>
	<p>
	</p>
	<form method='HEAD' action="{{ url('teams/nuevo') }}">
	<button type="submit" value="Nuevo Teams" class="btn btn-primary" name="">Nuevo Team</button>
	</form>
	<br>
	<tr>
	<table class="table table-striped">
		<thead>
		    <th scope="col"><strong>ID</strong></th>
		    <th scope="col"><strong>Nombre</strong></th>
		    <th scope="col"><strong></strong></th>
		    <th scope="col"><strong></strong></th>
		</thead>
		<tbody>
			@forelse ($teams as $team)
				<tr>
				<th scope="row">
					{{ $team->id }}
				</th>
				<th scope="row">
					<a href="/teams/{{ $team->id }}">									
						{{ $team->nameTeam }}
					</a>
				</th>
				<th scope="row">									
					<form method='HEAD' action="/teams/{{$team->id}}/editar">
						{{ csrf_field() }}
						<input style="text-align: center;" type="image" align="center" src="{{ asset('img/edit.png') }}" width="30" height="30">
					</form>
				</th>
				<th scope="row">
					<form method='post' action="/teams/{{$team->id}}">
						{{ csrf_field() }}
						{{ method_field('DELETE') }}					
						<input type="image" align="center" src="{{ asset('img/delete.png') }}" width="30" height="30">
					</form>
				</th>								
			@empty
				<li>No hay teams registrados</li>	
			@endforelse
				</tr>
		</tbody>		
	</table>
@endsection	