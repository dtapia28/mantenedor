@extends('Bases.dashboard')
@section('titulo', 'Resolutores')
@section('contenido')
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
 <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
	<h1>Listado de Resolutores</h1>
	<p>
	</p>
	@if($user[0]->nombre == "administrador")
	<form method='HEAD' action="{{ url('resolutors/nuevo') }}">
	<button type="submit" value="Nuevo Resolutor" class="btn btn-primary" name="">Nuevo</button>
	</form>
	@endif
	<br>
	<tr>
		<div class="card mb-3">
	    	<div class="card-header">
	        	<i class="fas fa-table"></i>
	            Resolutores</div>
	          <div class="card-body">
	            <div class="table-responsive">
	              <table class="table table-bordered table-striped table-hover" id="dataTable"width="100%"  cellspacing="0">
	                <thead>
	                	<tr>
						    <th scope="col"><strong>ID</strong></th>
						    <th scope="col"><strong>Nombre</strong></th>
						    @if($user[0]->nombre == "administrador")
						    <th scope="col"><strong></strong></th>
						    @endif
						    @if($user[0]->nombre == "administrador")
						    <th scope="col"><strong></strong></th>
						    @endif
						</tr>
					</thead>
					<tbody>
						@forelse ($resolutors as $resolutor)
						<tr>
							<th scope="row">
							    <a href="../public/resolutors/{{ $resolutor->id }}">					    
								{{ $resolutor->id }}
								</a>
							</th>
						<td>
						{{ $resolutor->nombreResolutor }}
						</td>
						@if($user[0]->nombre == "administrador")
						<td>									
						<form method='HEAD' action="../public/resolutors/{{$resolutor->id}}/editar">
							{{ csrf_field() }}						
						<input style="text-align: center;" type="image" align="center" src="{{ asset('img/edit.png') }}" width="30" height="30">
						</form>
						</td>
						@endif
						@if($user[0]->nombre == "administrador")
						<td>
							<form method='post' action="../public/resolutors/{{$resolutor->id}}">
							{{ csrf_field() }}
							{{ method_field('DELETE') }}						
						<input type="image" align="center" src="{{ asset('img/delete.png') }}" width="30" height="30">
						</form>
						</td>
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