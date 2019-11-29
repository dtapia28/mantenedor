@if(session()->has('msj'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('msj') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    </div>
@endif
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
		@if($user[0]->nombre == "administrador")
		<form method='HEAD' action="{{ url('users/nuevo') }}">
		<button type="submit" value="Nuevo Solicitante" class="btn btn-primary" name="">Nuevo</button>
		</form>
		@endif
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
							    @if($user[0]->nombre == "administrador")
							    <th scope="col"><strong></strong></th>
							    @endif
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
					@if($user[0]->nombre == "administrador")
					<th scope="row">
						<form method='post' action="{{ url('solicitantes/'.$solicitante->id)}}">
							{{ csrf_field() }}
							{{ method_field('DELETE') }}						
							<input type="image" align="center" src="{{ asset('img/delete.png') }}" width="30" height="30">
						</form>
					</th>
					@endif
				@empty	
				@endforelse
					</tr>
			</tbody>		
		</table>
	</div>
	</div>
	</div>
@endsection