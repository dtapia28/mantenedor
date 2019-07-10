@extends('Bases.dashboard')
@section('titulo', 'Teams')
@section('contenido')
		<h1>Listado de Teams</h1>
		<p>
		</p>
		<form method='HEAD' action="{{ url('teams/nuevo') }}">
		<button type="submit" value="Nuevo Teams" class="btn btn-primary" name="">Nuevo</button>
		</form>
		<br>
		              <table class="table table-bordered" id="dataTable" width="1300px" cellspacing="0">
		                <thead>
		                	<tr>
							    <th scope="col"><strong>ID</strong></th>
							    <th scope="col"><strong>Nombre</strong></th>
							    <th scope="col"><strong></strong></th>
							    <th scope="col"><strong></strong></th>
							</tr>
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
				@endforelse
					</tr>
			</tbody>		
		</table>
	</div>
	</div>
	</div>
@endsection	