@extends('Bases.dashboard')
@section('titulo', 'Solicitantes')
@section('contenido')
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
 <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
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
		              <table class="table table-bordered table-striped table-hover" id="dataTable" width="100%"  cellspacing="0">
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
						<a href="../public/solicitantes/{{ $solicitante->id }}">														    
						{{ $solicitante->id }}
						</a>
					</th>
					<th scope="row">
							{{ $solicitante->nombreSolicitante }}
					</th>
					<th scope="row">									
						<form method='HEAD' action="../public/solicitantes/{{$solicitante->id}}/editar">
							{{ csrf_field() }}						
							<input style="text-align: center;" type="image" align="center" src="{{ asset('img/edit.png') }}" width="30" height="30">
						</form>
					</th>
					<th scope="row">
						<form method='post' action="../public/solicitantes/{{$solicitante->id}}">
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