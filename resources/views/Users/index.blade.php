@extends('Bases.dashboard')

@section('css')
	<link href="{{ asset('vendor/DataTables/datatables.min.css') }}" rel="stylesheet" />
@endsection

@section('titulo', 'Usuarios')

@section('contenido')
@if(session()->has('msj'))
    <div class="alert alert-primary alert-dismissible fade show" role="alert">
    {{ session('msj') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    </div>
@endif
<div class="page-heading">
	<h1 class="page-title"><i class="fa fa-user-circle"></i> Usuarios</h1>
</div>
<div class="page-content fade-in-up">
	<div class="ibox">
		<div class="ibox-head">
			<div class="ibox-title">Listado de Usuarios</div>
			<div class="pull-right"><a class="btn btn-success" href="{{ url('users/nuevo') }}" style="white-space: normal;"><i class="fa fa-plus"></i> Nuevo Registro</a></div>
		</div>
		<div class="ibox-body">
			<div class="table-responsive">
				<table class="table table-striped table-bordered table-hover" id="dataTable" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th><strong>Usuario</strong></th>
							<th><strong>Rol</strong></th>
							<th><strong>Cambiar Contraseña</strong>
						</tr>
					</thead>
					<tbody>
						@forelse ($users as $us)
						<tr>	
							<td>
								{{ $us->name }}
							</td>
							<td>	
								<form method="POST" action="{{ url('users/modificar') }}" class="form-inline">
									{{ csrf_field() }}
									<?php
									echo "<select id='role".$us->id."' class='form-control col-md-7 mb-2 mr-sm-2 mb-sm-0' name='idRole'>";
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
									</select>&nbsp;
									<span style='display: none;' id="idLider{{$us->id}}"><input type="checkbox" name="lider" id="lider{{$us->id}}">&nbsp;Líder</span>
									<input type="hidden" value="{{ $us->id }}" name="idUsers">	
									<button type="submit" class="btn btn-primary btn-sm" style="cursor:pointer"><i class="fa fa-pencil"></i> Cambiar</button>
								</form>
							</td>	
							<td>
								<form method="POST" action="{{ url('users/cambiar') }}" class="form-inline">
									{{ csrf_field() }}
									<input class="form-control mb-2 mr-sm-2 mb-sm-0" type="password" name="cambiar">
									<input type="hidden" value="{{ $us->id }}" name="usuario">
									<button class="btn btn-primary btn-sm" type="submit" style="cursor:pointer"><i class="fa fa-check"></i> Aceptar</button>
								</form>
							</td>								
							@empty
						</tr>
						@endforelse
					</tbody>		
				</table>
			</div>
		</div>
	</div>
</div>
<?php
	foreach ($users as $us) {
		echo "<script type='text/javascript'>\n$(document).ready(function(){\n";

		echo "$('#role".$us->id."').on('change', function(){\nvar combo = document.getElementById('role".$us->id."');\nvar selected = combo.options[combo.selectedIndex].text;\nif(selected == 'resolutor' || selected == 'gestor'){\ndocument.getElementById('team".$us->id."').style.display = 'block';\n$.get('users/script', function(teams){\n$('#team".$us->id."').empty();\n$('#team".$us->id."').append(\"<option value=''>Seleccione un equipo</option>\");\n$.each(teams, function(index, value){\n $('#team".$us->id."').append(\"<option value='\"+index+\"'>\"+value+\"</option>\");\n});\ndocument.getElementById('idLider".$us->id."').style.display = 'block';\n});\n} else {\ndocument.getElementById('team".$us->id."').style.display = 'none';\ndocument.getElementById('idLider".$us->id."').style.display = 'none';\n}\n});\n});\n</script>\n";
	}
?>
@endsection

@section('script')
<script src="{{ asset('vendor/DataTables/datatables.min.js') }}" type="text/javascript"></script>
<script type="text/javascript">
	menu_activo('mUsuarios');
	$(function() {
		$('#dataTable').DataTable({
			"language": {
				"url": "{{ asset('vendor/DataTables/lang/spanish.json') }}"
			},
			pageLength: 10,
			stateSave: true,
		});
	});
	$(document).ready(function() {
		if (window.innerWidth < 768) {
			$('.btn').addClass('btn-sm');
		}
	});
</script>
@endsection
