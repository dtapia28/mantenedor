@extends('Bases.dashboard')

@section('css')
	<link href="{{ asset('vendor/DataTables/datatables.min.css') }}" rel="stylesheet" />
	<style type="text/css">
		.table thead th, .table td, .table th {
			vertical-align: middle;
		}
		.table th {
			text-align: center;
		}
	</style>
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
	<h1 class="page-title"><i class="fa fa-address-card"></i> Requerimientos</h1>
</div>
<div class="form-check form-check-inline">
	@php
		$estado = Request()->state;
		$valor = Request()->valorN;
		$solicitantereq = Request()->solicitante;
	@endphp
	{{-- {{dd($estado)}} --}}
	<form class="navbar-form navbar-left pull-right" method='GET' action="{{ url('requerimientos/') }}">
		<select id="state" class="custom-select" name="state">

			<option selected="true" disabled="disabled" value="">Escoja una opcion...</option>
			@if($user[0]->nombre == "gestor" or $user[0]->nombre == "supervisor" or $user[0]->nombre =="administrador")
			<option value="6" @if ($estado == "6") selected @endif>Por autorizar</option>
			@endif
                        @if($user[0]->nombre == "solicitante")
                            <option value="6" @if ($estado == "6") selected @endif>Por autorizar</option>		
                        @endif
                        @if($user[0]->nombre == "resolutor" and $lider == 1)
                            <option value="6" @if ($estado == "6") selected @endif>Por autorizar</option>
                        @endif
			@if($user[0]->nombre == "resolutor")
			<option value="7" @if ($estado == "7") selected @endif>Esperando autorización</option>
			@endif							
			<option value="0" @if ($estado == "0") selected @endif>Inactivo</option>
			<option value="1" @if ($estado == "1") selected @endif>Activo</option>
			<option value="2" @if ($estado == "2") selected @endif>% mayor o igual que</option>
			<option value="3" @if ($estado == "3") selected @endif>% menor o igual que</option>
			<option value="4" @if ($estado == "4") selected @endif>Vencidos</option>
			<option value="5" @if ($estado == "5") selected @endif>Por solicitante</option>
                        @if($user[0]->nombre != "administrador")
                        <option value="9" @if ($estado == "9") selected @endif>Mis solicitudes</option>
                        @endif
                        @if($user[0]->nombre == "resolutor" and $lider == 1)
                            <option value="10" @if ($estado == "10") selected @endif>Mis Requerimientos</option>
                        @endif
                        @if($user[0]->nombre == "supervisor")
                            <option value="10" @if ($estado == "10") selected @endif>Mis Requerimientos</option>
                        @endif                         
		</select>
		<input class="form-control col-md-12" type="number" id="valor"  @if ($valor=="" || $valor==null) style="display: none" @endif name="valorN" placeholder="porcentaje avance" min="1" max="100" @if ($valor!="") value="{{ $valor }}" @endif>
		<select id='solicitante' class='form-control col-md-12 custom-select' name='solicitante' @if ($solicitantereq=="" || $solicitantereq==null) style='display: none;' @endif>
			<option selected="true" disabled="disabled" value="">Escoja una opción...</option>
			@foreach($solicitantes as $solicitante)
				<option value={{ $solicitante->id }} @if ($solicitante->id == $solicitantereq) selected @endif>{{ $solicitante->nombreSolicitante }}</option>
			@endforeach							
		</select>
		<button class="btn btn-outline-primary my-2 my-sm-0" type="submit" style="cursor:pointer">Filtrar</button>
	</form>
</div>
<div class="page-content fade-in-up">
	<div class="ibox">
		<div class="ibox-head">
			<div class="ibox-title">
				Listado de Requerimientos
				@if ($valor == 1)
				<span class="badge badge-default"><i class="fa fa-circle text-success"></i> Activos</span>
				@else
				<span class="badge badge-default"><i class="fa fa-circle text-danger"></i> Inactivos</span>
				@endif
			</div>
			@if($user[0]->nombre == "solicitante" or $user[0]->nombre == "administrador")
			<div class="pull-right"><a class="btn btn-success" href="{{ url('requerimientos/nuevo') }}" style="white-space: normal;"><i class="fa fa-plus"></i> Nuevo Requerimiento</a></div>
			@endif                     
		</div>
		<div class="ibox-body">	
			<div class="table-responsive">
				<table class="table table-bordered table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
					<thead>
						<tr>
							@if($state == 0)
							<th>Activar</th>
							@endif
							@if($state == 6)
							@if($user[0]->nombre=="gestor" or $user[0]->nombre == "supervisor")
							<th>Autorizar</th>
							@endif
                                                        @if($user[0]->nombre == "resolutor" and $lider == 1)
							<th>Autorizar</th>
							@endif                                                        
							@endif							
							<th>Id</th>
							<th>Requerimiento/Tarea</th>
							<th>Fecha Solicitud</th>
							<th>Fecha Cierre</th>
							<th>Resolutor</th>
							<th>Avance (%)</th>
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
					<tbody style="font-size:13px">
						@forelse ($requerimientos as $requerimiento)
						<tr>
							@if($state == 6)
							@if($user[0]->nombre=="gestor" or $user[0]->nombre == "supervisor")
							<td id="tabla" scope="row">
								<form method="POST" action="{{ url("requerimientos/{$requerimiento->id}/autorizar") }}">
									{{ csrf_field() }}
									<button onclick="return confirm('�Est�s seguro/a de autorizar el cierre del requerimiento?')" type="submit" value="Nuevo Requerimiento" class="btn btn-success" name="">Autorizar</button>
								</form>
							</td>							
							@endif
                                                        @if($user[0]->nombre == "resolutor" and $lider == 1)
							<td id="tabla" scope="row">
								<form method="POST" action="{{ url("requerimientos/{$requerimiento->id}/autorizar") }}">
									{{ csrf_field() }}
									<button onclick="return confirm('�Est�s seguro/a de autorizar el cierre del requerimiento?')" type="submit" value="Nuevo Requerimiento" class="btn btn-success" name="">Autorizar</button>
								</form>
							</td>							
							@endif                                                        
							@endif
							@if($state == 0)
							<td id="tabla" scope="row">
								<form method="POST" action="{{ url("requerimientos/{$requerimiento->id}/activar") }}">
									{{ csrf_field() }}
									<button onclick="return confirm('�Est�s seguro/a de activar el requerimiento?')" type="submit" value="Nuevo Requerimiento" class="btn btn-success" name="">Activar</button>
								</form>
							</td>
							@endif
                                                        @if ($state == 1 or $state == 4)
							<td style="white-space: nowrap;">
                                                            @if($requerimiento->tipo == "tarea")
                                                                <a href="{{ url("requerimientos/{$requerimiento->idRequerimiento}") }}">
                                                            @else
								<a href="{{ url("requerimientos/{$requerimiento->id}") }}">                                                            
                                                            @endif
									{{ $requerimiento->id2 }}
								</a>					
							</td>
                                                        @else
                                                        <td style="white-space: nowrap;">
                                                            <a href="{{ url("requerimientos/{$requerimiento->id}") }}">{{ $requerimiento->id2 }}</a>
                                                        </td>
                                                        @endif
                                                        @if($state == 1 or $state == 4)
							<td style="font-size: 0.9rem; max-width: 15vw; overflow-wrap: break-word;">	
                                                            @if($requerimiento->tipo == "tarea")
                                                                {{ $requerimiento->titulo_tarea }}
                                                            @else
                                                                {{ $requerimiento->textoRequerimiento }}
                                                            @endif
							</td>
                                                        @else
                                                        <td style="font-size: 0.9rem; max-width: 15vw; overflow-wrap: break-word;">
                                                              {{ $requerimiento->textoRequerimiento }}  
                                                        </td>
                                                        @endif
							<td style="font-size:13px !important">	
								{{ date('Y-m-d', strtotime($requerimiento->fechaSolicitud)) }}
							</td>
							@if($requerimiento->fechaRealCierre != "")
							<td style="font-size:13px !important">	
								{{ date('Y-m-d', strtotime($requerimiento->fechaRealCierre)) }}
							</td>
							@else
							<td style="font-size:13px !important">	
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
								<span class="badge badge-default">Al dia <i class="fa fa-circle text-success"></i></span>
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
							@if($state != 6 and $state != 7 and $state != 0)    
								@if($user[0]->nombre == "resolutor" or $user[0]->nombre == "administrador")
									@if($requerimiento->tipo != "tarea")
									<form method='HEAD' action="{{ url("requerimientos/{$requerimiento->id}/anidar") }}">
										{{ csrf_field() }}
										<button type="submit" class="btn btn-warning btn-sm" data-toggle="tooltip" data-original-title="Anidar" style="cursor:pointer"><i class="fa fa-compress"></i></button>
									</form>
									@endif
								@endif						
								@if($user[0]->nombre == "resolutor" or $user[0]->nombre == "administrador" or $user[0]->nombre == "supervisor")
                                    @if($user[0]->nombre == "resolutor" and $res->id == $requerimiento->resolutor)
                                    &nbsp;
                                        @if($requerimiento->tipo == "tarea")
                                            <form method='GET' action="{{ url("/requerimientos/{$requerimiento->idRequerimiento}/tareas/{$requerimiento->id}/terminar")}}">					
                                                {{ csrf_field() }}
                                                <button type="submit" class="btn btn-success btn-sm" data-toggle="tooltip" data-original-title="Terminar" style="cursor:pointer"><i class="fa fa-check"></i></button>
                                                <input type="hidden" name="tarea" value={{$requerimiento->id}}>
                                                <input type="hidden" name="req" value="{{ $requerimiento->idRequerimiento }}">
                                            </form>                                        
                                        @else
                                        <form method='HEAD' action="{{ url("requerimientos/{$requerimiento->id}/terminado") }}">
                                            {{ csrf_field() }}
                                            <button type="submit" class="btn btn-success btn-sm" data-toggle="tooltip" data-original-title="Terminar" style="cursor:pointer"><i class="fa fa-check"></i></button>
                                        </form>
                                        @endif
                                    @endif
                                    @if($user[0]->nombre == "supervisor" and $res->id == $requerimiento->resolutor)
                                    &nbsp;
                                        @if($requerimiento->tipo == "tarea")
                                            <form method='GET' action="{{ url("/requerimientos/{$requerimiento->idRequerimiento}/tareas/{$requerimiento->id}/terminar")}}">					
                                                {{ csrf_field() }}
                                                <button type="submit" class="btn btn-success btn-sm" data-toggle="tooltip" data-original-title="Terminar" style="cursor:pointer"><i class="fa fa-check"></i></button>
                                                <input type="hidden" name="tarea" value={{$requerimiento->id}}>
                                                <input type="hidden" name="req" value="{{ $requerimiento->idRequerimiento }}">
                                            </form>                                        
                                        @else
                                        <form method='HEAD' action="{{ url("requerimientos/{$requerimiento->id}/terminado") }}">
                                            {{ csrf_field() }}
                                            <button type="submit" class="btn btn-success btn-sm" data-toggle="tooltip" data-original-title="Terminar" style="cursor:pointer"><i class="fa fa-check"></i></button>
                                        </form>
                                        @endif
                                    @endif
                                    @if( $user[0]->nombre == "administrador")
                                    &nbsp;
                                        @if($requerimiento->tipo == "tarea")
                                            <form method='GET' action="{{ url("/requerimientos/{$requerimiento->idRequerimiento}/tareas/{$requerimiento->id}/terminar")}}">					
                                                {{ csrf_field() }}
                                                <button type="submit" class="btn btn-success btn-sm" data-toggle="tooltip" data-original-title="Terminar" style="cursor:pointer"><i class="fa fa-check"></i></button>
                                                <input type="hidden" name="tarea" value={{$requerimiento->id}}>
                                                <input type="hidden" name="req" value="{{ $requerimiento->idRequerimiento }}">
                                            </form>                                        
                                        @else
                                        <form method='HEAD' action="{{ url("requerimientos/{$requerimiento->id}/terminado") }}">
                                            {{ csrf_field() }}
                                            <button type="submit" class="btn btn-success btn-sm" data-toggle="tooltip" data-original-title="Terminar" style="cursor:pointer"><i class="fa fa-check"></i></button>
                                        </form>
                                        @endif
                                    @endif
                                    @endif 
							@if($user[0]->nombre == "solicitante" or $user[0]->nombre == "administrador")
								&nbsp;
                                                                @if($requerimiento->tipo == "tarea")
								<form method='HEAD' action="{{ url('/requerimientos/'.$requerimiento->idRequerimiento.'/tareas/'.$requerimiento->id.'/editar') }}">
                                                                    {{ csrf_field() }}
                                                                    <button type="submit" class="btn btn-primary btn-sm" data-toggle="tooltip" data-original-title="Editar" style="cursor:pointer"><i class="fa fa-pencil"></i></button>
								</form>                                                                
                                                                @else
								<form method='HEAD' action="{{ url("requerimientos/{$requerimiento->id}/editar") }}">
									{{ csrf_field() }}
									<button type="submit" class="btn btn-primary btn-sm" data-toggle="tooltip" data-original-title="Editar" style="cursor:pointer"><i class="fa fa-pencil"></i></button>
								</form>
                                                                @endif
							@endif
							@if($user[0]->nombre == "resolutor" or $user[0]->nombre == "administrador" or $user[0]->nombre == "supervisor")
								&nbsp;
								<form method='POST' action="{{ url("requerimientos/{$requerimiento->id}/adjuntar") }}">
									{{ csrf_field() }}
									<button type="submit" class="btn btn-info btn-sm" data-toggle="tooltip" data-original-title="Adjuntar" style="cursor:pointer"><i class="fa fa-paperclip"></i></button>
								</form>
							@endif                                                        
                                                        @if($user[0]->nombre == "resolutor" and $lider == 1)
                                                        &nbsp;
                                                                @if($requerimiento->tipo == "tarea")
								<form method='HEAD' action="{{ url('/requerimientos/'.$requerimiento->idRequerimiento.'/tareas/'.$requerimiento->id.'/editar') }}">
                                                                    {{ csrf_field() }}
                                                                    <button type="submit" class="btn btn-primary btn-sm" data-toggle="tooltip" data-original-title="Editar" style="cursor:pointer"><i class="fa fa-pencil"></i></button>
								</form>                                                                
                                                                @else
								<form method='HEAD' action="{{ url("requerimientos/{$requerimiento->id}/editar") }}">
									{{ csrf_field() }}
									<button type="submit" class="btn btn-primary btn-sm" data-toggle="tooltip" data-original-title="Editar" style="cursor:pointer"><i class="fa fa-pencil"></i></button>
								</form>
                                                                @endif
                                                        @endif                                            
							@if($user[0]->nombre == "solicitante" or $user[0]->nombre == "administrador")
								&nbsp;
                                                                @if($requerimiento->tipo == "tarea")
								<form method='POST' action="{{ url('/requerimientos/'.$requerimiento->idRequerimiento.'/tareas/'.$requerimiento->id.'/eliminar') }}">
                                                                    {{ csrf_field() }}
                                                                    {{ method_field('DELETE') }}						
                                                                    <button type="submit" class="btn btn-danger btn-sm" data-toggle="tooltip" data-original-title="Eliminar" style="cursor:pointer"><i class="fa fa-trash"></i></button>
                                                                    <input type="hidden" name="tarea" value={{$requerimiento->id}}>
                                                                    <input type="hidden" name="req" value={{$requerimiento->idRequerimiento}}>
								</form                                                                
                                                                @else
								<form method='POST' action="{{ url('/requerimientos/'.$requerimiento->id) }}">
									{{ csrf_field() }}
									{{ method_field('DELETE') }}						
									<button type="submit" class="btn btn-danger btn-sm" data-toggle="tooltip" data-original-title="Eliminar" style="cursor:pointer"><i class="fa fa-trash"></i></button>
								</form>
                                                                @endif
							@endif
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
	$(document).ready(function() {
		let estado = $('#state').val();
		if (estado != "" && estado != null) {
			storeCacheFilter();
		} else {
			sessionStorage.clear();
		}
		$('#state').on('change', function() {
			var role = $(this).val();
			if(role == "2" || role == "3") {
				document.getElementById("valor").style.display = "block";
				document.getElementById("solicitante").style.display = "none";
				$('#valor').prop('required', true);
				$('#solicitante').val("");
			} else if (role == "5"){
				document.getElementById("solicitante").style.display = "block";
				document.getElementById("valor").style.display = "none";
				$('#valor').val("");
				$('#valor').prop('required', false);
			} else {
				document.getElementById("valor").style.display = "none";
				document.getElementById("solicitante").style.display = "none";
				$('#valor').prop('required', false);
				$('#valor').val("");
				$('#solicitante').val("");
			}
		});
		menu_activo('mRequerimientos');
		if (window.innerWidth < 768) {
        	$('.btn').addClass('btn-sm');
	    }
	});
	$(function() {
		$('#dataTable').DataTable({
			"language": {
				"url": "{{ asset('vendor/DataTables/lang/spanish.json') }}"
			},
			pageLength: 10,
			stateSave: true,
		});
	});
	function confirmar(){
		var respuesta = confirm("�Est�s seguro/a que desea activar el requerimiento?");
		if (respuesta == true) {
			return true;
		} else {
			return false;
		}
	}
	function storeCacheFilter() {
		if (typeof(Storage) !== 'undefined') {
            sessionStorage.setItem('stState', $('#state').val());
            sessionStorage.setItem('stValor', $('#valor').val());
			sessionStorage.setItem('stSolicitante', $('#solicitante').val());
			sessionStorage.setItem('stFiltroActivo', 1);
		}
	}
</script>		
@endsection