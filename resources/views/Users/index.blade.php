@extends('Bases.dashboard')
@section('titulo', 'Usuarios')
@section('contenido')
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
 <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
	<h1>Usuarios</h1>
	<p>
	</p>
	<br>
	<tr>
		<div class="card mb-3">
	    	<div class="card-header">
	        	<i class="fas fa-table"></i>
	            Usuarios</div>
	          <div class="card-body">
	            <div class="table-responsive">
	              <table class="table table-bordered table-striped table-hover" id="dataTable"width="100%"  cellspacing="0">
	                <thead>
	                	<tr>
						    <th scope="col"><strong>ID</strong></th>
						    <th scope="col"><strong>Usuario</strong></th>
						    <th scope="col"><strong></strong></th>
						    <th scope="col"><strong></strong></th>
						</tr>
					</thead>
					<tbody>
						@forelse ($users as $us)
						<tr>
							<th scope="row">
							    <a href="../resolutors/{{ $us->id }}">					    
								{{ $us->id }}
								</a>
							</th>
						<td>
						{{ $us->name }}
						</td>
						<td>	
						<form method="POST" action="{{ url('users/modificar') }}">
						{{ csrf_field() }}								
			                <select class="form-control col-md-5" name="role_id">
			                @foreach($relaciones as $relacion)
				                @if($us->id == $relacion->user_id)
				                	@foreach($roles as $role)
					                	<optgroup>
					                    	<option value="{{ $role->id }}" @if($role->id == $relacion->role_id){{ 'selected' }}@endif>
					                    		{{ $role->nombre }}
					                    	</option>
					                    </optgroup>
					                @endforeach    
				            	@endif
				            @endforeach
			                </select>
						<td>
						<input type="hidden" value="{{ $us->id }}" name="user_id">	
						<button type="submit" class="btn btn-success">Modificar</button>
						</form>
						</td>								
						@empty	
						@endforelse
				</tr>
		</tbody>		
	</table>
</div>
</div>
</div>
@endsection