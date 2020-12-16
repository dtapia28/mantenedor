@extends('Bases.dashboard2')

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
    <form class="navbar-form navbar-left pull-right" method='GET' action="{{ url('/for_date') }}">
        <select id="state" class="custom-select" name="tipo">

            <option selected="true" disabled="disabled" value="">Escoja una opcion...</option>
            <option value="1">Hoy</option>
            <option value="2">Próximos 2 días</option>
            <option value="3">Próximos 3 días</option>
            <option value="4">Próximos 7 días</option>
            <option value="5">Próximos 10 días</option>
        </select>
        <button class="btn btn-outline-primary my-2 my-sm-0" type="submit" style="cursor:pointer">Filtrar</button>
    </form>
</div>
<div class="page-content fade-in-up">
    <div class="ibox">
        <div class="ibox-head">
            @if($user->nombre == "solicitante" or $user->nombre == "administrador")
            <div class="pull-right"><a class="btn btn-success" href="{{ url('requerimientos/nuevo') }}" style="white-space: normal;"><i class="fa fa-plus"></i> Nuevo Requerimiento</a></div>
            @endif                     
        </div>
        <div class="ibox-body">	
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>							
                            <th>Id</th>
                            <th>Requerimiento/Tarea</th>
                            <th>Fecha Solicitud</th>
                            <th>Fecha Cierre</th>
                            <th>Resolutor</th>
                            <th>Avance (%)</th>
                            <th>Estatus</th>
                            <th>Anidados</th>
                            <th>Acciones</th>
                            {{-- @if($user->nombre == "administrador" or $user->nombre == "resolutor")
                            <th>Anidar</th>
							@endif
							@if($user->nombre == "resolutor" or $user->no                                mbre == "administrador")
                            <th></th>
							@endif
							@if($user->nombre == "solicitante" or $user->nombre ==                             "administrador")
                        <th></th>
							@endif
							@if($user->nombre == "solicitante" or $user->no                                mbre == "administrador")
                            <th></th>
						                        @endif --}}                    
						</tr>
	                    </thead>
                    <tbody style="font-size:13px">
                        @forelse ($requerimientos as $requerimiento)
                        <tr>
                            <td style="white-space: nowrap;">
                                @if($requerimiento->tipo == "tarea")
                                <a href="{{ url("requerimientos/{$requerimiento->idRequerimiento}") }}">
                                    @else
                                    <a href="{{ url("requerimientos/{$requerimiento->id}") }}">                                                            
                                        @endif
                                        {{ $requerimiento->id2 }}
                                    </a>					
                            </td>
                            <td style="font-size: 0.9rem; max-width: 15vw; overflow-wrap: break-word;">	
                                @if($requerimiento->tipo == "tarea")
                                {{ $requerimiento->titulo_tarea }}
                                @else
                                {{ $requerimiento->textoRequerimiento }}
                                @endif
                            </td>
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
                            <td class="text-center">
                                @if($requerimiento->status == 1)
                                <span class="badge badge-default">Al dia <i class="fa fa-circle text-success"></i></span>
                                @elseif($requerimiento->status == 2)
                                <span class="badge badge-default">Por Vencer <i class="fa fa-circle text-warning"></i></span>
                                @else
                                <span class="badge badge-default">Vencido <i class="fa fa-circle text-danger"></i></span>
                                @endif					
                            </td>
                            <td class="text-center">
                                <?php
                                $conteo = 0;
                                foreach ($anidados as $anidado) {
                                    if ($requerimiento->id == $anidado->idRequerimientoBase) {
                                        $conteo++;
                                    }
                                }
                                if ($conteo > 0) {
                                    echo '<span class="badge badge-success">Si</span>';
                                } else {
                                    echo '<span class="badge badge-danger">No</span>';
                                }
                                ?>
                            </td>
                            <td>
                                <div scope="row" class="btn-group">   
                                    @if($user->nombre == "resolutor" or $user->nombre == "administrador")
                                    @if($requerimiento->tipo != "tarea")
                                    <form method='HEAD' action="{{ url("requerimientos/{$requerimiento->id}/anidar") }}">
                                        {{ csrf_field() }}
                                        <button type="submit" class="btn btn-warning btn-sm" data-toggle="tooltip" data-original-title="Anidar" style="cursor:pointer"><i class="fa fa-compress"></i></button>
                                    </form>
                                    @endif
                                    @endif						
                                    @if($user->nombre == "resolutor" or $user->nombre == "administrador" or $user->nombre == "supervisor")
                                    @if($user->nombre == "resolutor" and $res->id == $requerimiento->resolutor)
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
                                    @if($user->nombre == "supervisor" and $res->id == $requerimiento->resolutor)
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
                                    @if( $user->nombre == "administrador")
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
                                    @if($user->nombre == "solicitante" or $user->nombre == "administrador")
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
                                    @if($user->nombre == "resolutor" and $lider == 1)
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
                                    @if($user->nombre == "solicitante" or $user->nombre == "administrador")
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
		menu_activo('mDate');
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