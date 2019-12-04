@extends('Bases.dashboard')

@section('css')
	<link href="{{ asset('vendor/DataTables/datatables.min.css') }}" rel="stylesheet" />
@endsection

@section('titulo', "Requerimientos")

@section('contenido')
@if(session()->has('msj'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
	{{ session('msj') }}
	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		<span aria-hidden="true">&times;</span>
	</button>
</div>
@endif
<div class="page-heading">
	<h1 class="page-title"><i class="fa fa fa-address-card"></i> Requerimientos</h1>
</div>
<div class="form-check form-check-inline">
	<form class="navbar-form navbar-left pull-right" method='GET' action="{{ url('requerimientos/') }}">
		<select id="state" class="custom-select" name="state">
			<option selected="true" disabled="disabled" value="">Escoja una opción...</option>
			@if($user[0]->nombre == "gestor")
			<option value="6">Por autorizar</option>
			@endif
			@if($user[0]->nombre == "resolutor")
			<option value="7">Esperando autorización</option>
			@endif							
			<option value="0">Inactivo</option>
			<option value="1">Activo</option>
			<option value="2">% mayor o igual que</option>
			<option value="3">% menor o igual que</option>
			<option value="4">Vencidos</option>
			<option value="5">Por solicitante</option>     	
		</select>
		<input class="form-control col-md-12" type="number" id="valor" style="display: none" name="valorN" placeholder="porcentaje avance">
		<select id='solicitante' class='form-control col-md-12' name='solicitante' style='display: none;'>
			<optgroup>
				@foreach($solicitantes as $solicitante)
				<option value={{ $solicitante->id }}>{{ $solicitante->nombreSolicitante }}</option>
				@endforeach							
			</optgroup>
		</select>
		<button class="btn btn-outline-primary my-2 my-sm-0" type="submit" style="cursor:pointer">Filtrar</button>
	</form>
</div>
<div class="page-content fade-in-up">
	<div class="ibox">
		<div class="ibox-head">
			<div class="ibox-title">Listado de Requerimientos
				@if ($valor == 1)
				<span class="badge badge-default"><i class="fa fa-circle text-success"></i> Activos</span>
				@else
				<span class="badge badge-default"><i class="fa fa-circle text-danger"></i> Inactivos</span>
				@endif
			</div>
			@if($user[0]->nombre == "solicitante" or $user[0]->nombre == "administrador")
				<div class="d-flex align-content-end"><a class="btn btn-success" href="{{ url('requerimientos/nuevo') }}"><i class="fa fa-plus"></i> Nuevo Requerimiento</a></div>
			@endif
		</div>
		<div class="ibox-body">	
			<div class="table-responsive">
				<table class="table table-bordered table-striped table-hover" id="dataTable" width="100%"  cellspacing="0">
					<thead>
						<tr>
							@if($state == 0)
							<th>Activar</th>
							@endif
							@if($state == 7)
							<th>Activar</th>
							@endif							
							@if($state == 6)
							@if($user[0]->nombre=="gestor" or $user[0]->nombre == "supervisor")
							<th>Autorizar</th>
							@endif
							@endif							
							<th>Id</th>
							<th>Requerimiento</th>
							<th>Fecha Solicitud</th>
							<th>Fecha Cierre</th>
							<th>Resolutor</th>
							<th>Ejecutado (%)</th>
							@if($state != 0)
							<th>Estatus</th>
							@endif
							@if($state != 6 and $state !=0)
							<th>Anidados</th>
							@endif
							<th>Acciones</th>
							{{-- @if($user[0]->nombre == "administrador" or $user[0]->nombre == "resolutor")
							<th>Anidar</th>
							@endif
							@if($user[0]->nombre == "resolutor" or $user[0]->nombre == "administrador")
							<th></th>
							@endif
							@if($user[0]->nombre == "solicitante" or $user[0]->nombre == "administrador")
							<th></th>
							@endif
							@if($user[0]->nombre == "solicitante" or $user[0]->nombre == "administrador")
							<th></th>
							@endif --}}
						</tr>
					</thead>
					<tbody>
						@forelse ($requerimientos as $requerimiento)
						<tr>
							@if($state == 6)
							@if($user[0]->nombre=="gestor" or $user[0]->nombre == "supervisor")
							<td id="tabla" scope="row">
								<form method="POST" action="{{ url("requerimientos/{$requerimiento->id}/autorizar") }}">
									{{ csrf_field() }}
									<button onclick="return confirm('¿Estás seguro/a de autorizar el cierre del requerimiento?')" type="submit" value="Nuevo Requerimiento" class="btn btn-success" name="">Autorizar</button>
								</form>
							</td>							
							@endif
							@endif
							@if($state == 0)
							<td id="tabla" scope="row">
								<form method="POST" action="{{ url("requerimientos/{$requerimiento->id}/activar") }}">
									{{ csrf_field() }}
									<button onclick="return confirm('¿Estás seguro/a de activar el requerimiento?')" type="submit" value="Nuevo Requerimiento" class="btn btn-success" name="">Activar</button>
								</form>
							</td>
							@endif
							@if($state == 7)
							<th id="tabla" scope="row">
								<form method="POST" action="{{ url("requerimientos/{$requerimiento->id}/activar") }}">
									{{ csrf_field() }}
									<button onclick="return confirm('¿Estás seguro/a de activar el requerimiento?')" type="submit" value="Nuevo Requerimiento" class="btn btn-success" name="">Activar</button>
								</form>
							</th>
							@endif							
							<td style="white-space: nowrap;">
								<a href="{{ url("requerimientos/{$requerimiento->id}") }}">
									{{ $requerimiento->id2 }}
								</a>					
							</td>
							<td>	
								{{ $requerimiento->textoRequerimiento }}
							</td>				
							<td>	
								{{ date('Y-m-d', strtotime($requerimiento->fechaSolicitud)) }}
							</td>
							@if($requerimiento->fechaRealCierre != "")
							<td>	
								{{ date('Y-m-d', strtotime($requerimiento->fechaRealCierre)) }}
							</td>
							@else
							<td>	
								{{ date('Y-m-d', strtotime($requerimiento->fechaCierre)) }}
							</td>
							@endif
							<td>								
								@forelse ($resolutors as $resolutor)
								@if ($requerimiento->resolutor == $resolutor->id)			
								{{ $resolutor->nombreResolutor }}
								@endif
								@empty
								@endforelse	
							</td>
							<td>
								{{ $requerimiento->porcentajeEjecutado }}
							</td>
							@if($state != 0)
							<td class="text-center">
								@if($requerimiento->status == 1)
								<span class="badge badge-default">Al día <i class="fa fa-circle text-success"></i></span>
								@elseif($requerimiento->status == 2)
								<span class="badge badge-default">Por Vencer <i class="fa fa-circle text-warning"></i></span>
								@else
								<span class="badge badge-default">Vencido <i class="fa fa-circle text-danger"></i></span>
								@endif					
							</td>
							@endif
							@if($state != 6 && $state !=0)
							<td class="text-center">
								<?php
								$conteo = 0;
								foreach ($anidados as $anidado) {
									if ($requerimiento->id == $anidado->idRequerimientoBase) {
										$conteo++;
									}
								}
								if ($conteo>0) {
									echo '<span class="badge badge-success">Si</span>';
								} else {
									echo '<span class="badge badge-danger">No</span>';
								}
								?>
							</td>
							@endif
							<td>
							<div scope="row" class="btn-group">
							@if($user[0]->nombre == "resolutor" or $user[0]->nombre == "administrador")
								<form method='HEAD' action="{{ url("requerimientos/{$requerimiento->id}/anidar") }}">
									{{ csrf_field() }}
									<button type="submit" class="btn btn-warning btn-sm" data-toggle="tooltip" data-original-title="Anidar" style="cursor:pointer"><i class="fa fa-compress"></i></button>
								</form>
							@endif						
							@if($user[0]->nombre == "resolutor" or $user[0]->nombre == "administrador")
								&nbsp;
								<form method='HEAD' action="{{ url("requerimientos/{$requerimiento->id}/terminado") }}">
									{{ csrf_field() }}
									<button type="submit" class="btn btn-success btn-sm" data-toggle="tooltip" data-original-title="Terminar" style="cursor:pointer"><i class="fa fa-check"></i></button>
								</form>
							@endif
							@if($user[0]->nombre == "solicitante" or $user[0]->nombre == "administrador")
								&nbsp;
								<form method='HEAD' action="{{ url("requerimientos/{$requerimiento->id}/editar") }}">
									{{ csrf_field() }}
									<button type="submit" class="btn btn-primary btn-sm" data-toggle="tooltip" data-original-title="Editar" style="cursor:pointer"><i class="fa fa-pencil"></i></button>
								</form>
							@endif
							@if($user[0]->nombre == "solicitante" or $user[0]->nombre == "administrador")
								&nbsp;
								<form method='POST' action="{{ url('/requerimientos/'.$requerimiento->id) }}">
									{{ csrf_field() }}
									{{ method_field('DELETE') }}						
									<button type="submit" class="btn btn-danger btn-sm" data-toggle="tooltip" data-original-title="Eliminar" style="cursor:pointer"><i class="fa fa-trash"></i></button>
								</form>
							@endif
							</div>
							</td>
						</tr>										               	
						@empty
						@endforelse
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
@endsection

@section('script')
<script src="{{ asset('vendor/DataTables/datatables.min.js') }}" type="text/javascript"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$('#state').on('change', function(){
			var role = $(this).val();
			if(role == 2 || role ==3){
				document.getElementById("valor").style.display = "block";
				document.getElementById("solicitante").style.display = "none";
			} else if (role == 5){
				document.getElementById("solicitante").style.display = "block";
				document.getElementById("valor").style.display = "none";
			} else {
				document.getElementById("valor").style.display = "none";
				document.getElementById("solicitante").style.display = "none";	
			}
		});
	});
	$(function() {
		$('#dataTable').DataTable({
			"language": {
				"url": "{{ asset('vendor/DataTables/lang/spanish.json') }}"
			},
			pageLength: 10,
			
		});
	})
	function confirmar(){
		var respuesta = confirm("¿Estás seguro/a que desea activar el requerimiento?");

		if (respuesta == true) {
			return true;
		} else {
			return false;
		}
	}
</script>		
@endsection
