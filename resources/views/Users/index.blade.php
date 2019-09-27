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
<div class="form-check form-check-inline">
	<form method='GET' action="{{ url('users/nuevo') }}">
		<button type="submit" value="Nuevo Usuario" class="btn btn-primary" name="">Nuevo</button>
	</form>
</div>
<br>
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
							<th scope="col-md-3"><strong>Usuario</strong></th>
							<th scope="col-md-3"><strong></strong></th>
							<th scope="col"><strong></strong></th>
						</tr>
					</thead>
					<tbody>
						@forelse ($users as $us)
						<tr>
							<td>
								{{ $us->id }}
							</td>	
							<td>
								{{ $us->name }}
							</td>
							<td>	
								<form method="POST" action="{{ url('users/modificar') }}">
									{{ csrf_field() }}
									<?php
									echo "<select id='role".$us->id."' class='form-control col-md-6' name='idRole'>";
									?>
										@foreach($relaciones as $relacion)
										@if($us->id == $relacion->user_id)
										@foreach($roles as $role)
											<option value="{{ $role->id }}" @if($role->id == $relacion->role_id){{ 'selected' }}@endif>
												{{ $role->nombre }}
											</option>
										@endforeach    
										@endif
										@endforeach
									</select>
									<?php
									echo "<select id='team".$us->id."' class='form-control col-md-8' name='idTeam' style='display: none;'>";
									?>
									</select>
							</td>		
							<td>
									<input type="hidden" value="{{ $us->id }}" name="idUsers">	
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
		<?php
		foreach ($users as $us) {
			echo "<script type='text/javascript'>\n$(document).ready(function(){\n";

			echo "$('#role".$us->id."').on('change', function(){\nvar role = $(this).val();\nif(role == 4){\ndocument.getElementById('team".$us->id."').style.display = 'block';\n$.get('/users/script', function(teams){\n$('#team".$us->id."').empty();\n$('#team".$us->id."').append(\"<option value=''>Seleccione un equipo</option>\");\n$.each(teams, function(index, value){\n $('#team".$us->id."').append(\"<option value='\"+index+\"'>\"+value+\"</option>\");\n});\n});\n} else {\ndocument.getElementById('team".$us->id."').style.display = 'none';\n}\n});\n});\n</script>\n";
		}
		?>	
@endsection