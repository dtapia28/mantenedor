@extends('Bases.dashboard')
@section('titulo', 'Solicitantes')
@section('contenido')
		<h1>Listado de Solicitantes</h1>
		<p>
		</p>
		<form method='HEAD' action="{{ url('solicitantes/nuevo') }}">
		<button type="submit" value="Nuevo Solicitante" class="btn btn-primary" name="">Nuevo</button>
		</form>
		<br>
			<div class="card mb-3">
		    	<div class="card-header">
		        	<i class="fas fa-table"></i>
		            Solicitantes</div>
		          <div class="card-body">
		            <div class="table-responsive">
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
							<input style="text-align: center;" type="image" align="center" src="{{ asset('img/edit.png') }}" width="30" height="30">
						</form>
					</th>
					<th scope="row">
						<form method='post' action="/solicitantes/{{$solicitante->id}}">
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