@extends('Bases.dashboard')
@section('titulo', "Detalle de Requerimientos")

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
	<h1 class="page-title"><i class="fa fa-address-card"></i> Requerimientos</h1>
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><i class="fa fa-file-text-o"></i> Detalle del Requerimiento</li>
	</ol>
</div>
<div class="page-content fade-in-up">
	<div class="row">
		<div class="col-md-12">
			<div class="ibox">
				@if($resolutor->idUser != $user[0]->idUser)
				@if($lider == 1 and $requerimiento->aprobacion == 4)
				<div class="ibox-head">
					<div class="ibox-title">Datos del Requerimiento</div>
					<div class="pull-right"><a class="btn btn-primary" onclick="return confirm('¿Estás seguro/a de autorizar el cierre del requerimiento?')" href="{{ url('requerimientos/'.$requerimiento->id.'/aceptar') }}" style="white-space: normal;"><i class="fa fa-check"></i> Aceptar</a> <a class="btn btn-outline-danger" href="{{ url('requerimientos/'.$requerimiento->id.'/rechazar') }}" style="white-space: normal;"><i class="fa fa-close"></i> Rechazar</a></div>
				</div>
				@endif
				@endif
				@if($user[0]->nombre=="supervisor" and $requerimiento->aprobacion == 4)
				<div class="ibox-head">
					<div class="ibox-title">Datos del Requerimiento</div>
					<div class="pull-right"><a class="btn btn-primary" onclick="return confirm('¿Estás seguro/a de autorizar el cierre del requerimiento?')" href="{{ url('requerimientos/'.$requerimiento->id.'/aceptar') }}" style="white-space: normal;"><i class="fa fa-check"></i> Aceptar</a> <a class="btn btn-outline-danger" href="{{ url('requerimientos/'.$requerimiento->id.'/rechazar') }}" style="white-space: normal;"><i class="fa fa-close"></i> Rechazar</a></div>
				</div>
				@endif				
				<div class="ibox-body">
					<div class="row">
						<div class="col-md-6">
                                                    @if($id2 == "INC")
							<h2>Incidente {{ $requerimiento->id2 }}</h2>
                                                    @else
                                                        <h2>Requerimiento {{ $requerimiento->id2 }}</h2>
                                                    @endif
							<br>
							<table class="table table-condensed">	
								<tr>
									<td width="40%"><strong>Solicitante</strong></td>
									<td width="60%">{{ $solicitante->nombreSolicitante }}</td>
								</tr>
								<tr>
									<td><strong>Resolutor</strong></td>
									<td>{{ $resolutor->nombreResolutor }}</td>
								</tr>
								@if($user[0]->nombre == "resolutor" or $user[0]->nombre == "administrador")
								<tr>
									<td><strong>Comentario</strong></td>
									<td>{{ $requerimiento->comentario }}</td>
								</tr>
								@endif
								<tr>
									<td><strong>Solicitud</strong></td>
									<td>{{ $requerimiento->textoRequerimiento }}</td>
								</tr>
								@forelse ($priorities as $priority)
								@if ($priority->id == $requerimiento->idPrioridad)
								<tr>
									<td><strong>Prioridad</strong></td>
									<td>{{ $priority->namePriority }}</td>
								</tr>								
								@endif
								@empty
								@endforelse
								<tr>
									<td><strong>Fecha original de requerimiento</strong></td>
									<td>{{ date('d-m-Y', strtotime($requerimiento->fechaEmail)) }}</td>
								</tr>
								<tr>
									<td><strong>Fecha de inicio seguimiento</strong></td>
									<td>{{ date('d-m-Y', strtotime($requerimiento->fechaSolicitud)) }}</td>
								</tr>
								@forelse ($teams as $team)
								@forelse ($resolutors as $resolutor)
								@if ($resolutor->idTeam == $team->id)
								@if ($resolutor->id == $requerimiento->idResolutor)
								<tr>
									<td><strong>Equipo</strong></td>
									<td>{{ $team->nameTeam }}</td>
								</tr>
								@endif
								@endif
								@empty
								@endforelse
								@empty
								@endforelse
								@forelse ($resolutors as $resolutor)
								@if ($resolutor->id == $requerimiento->idResolutor)
								<tr>
									<td><strong>Resolutor</strong></td>
									<td>{{ $resolutor->nombreResolutor }}</td>
								</tr>
								@endif
								@empty
								@endforelse
								<tr>
									<td><strong>Fecha solicitada de cierre</strong></td>
									<td>{{ date('d-m-Y', strtotime($requerimiento->fechaCierre)) }}</td>
								</tr>
								<tr>
									<td><strong>Fecha real cierre</strong></td>
									@if ($requerimiento->fechaRealCierre != null)
									<td>{{date('d-m-Y', strtotime($requerimiento->fechaRealCierre)) }}</td>
									@else
									<td></td>
									@endif
								</tr>
								<tr>
									<td><strong>Número de cambios</strong></td>
									<td>{{ $requerimiento->numeroCambios }}</td>
								</tr>
								<tr>
									<td><strong>Status de cambio</strong></td>
									@if ($requerimiento->numeroCambios <=1)
									<td>V</td>
									@elseif ($requerimiento->numeroCambios <=3)
									<td>A</td>
									@else
									<td>R</td>
									@endif
								</tr>
								<tr>
									<td><strong>Porcentaje ejecutado</strong></td>
									<td>{{ $requerimiento->porcentajeEjecutado }}%</td>
								</tr>
								@if($requerimiento->cierre != "")
								<tr>
									<td><strong>Cierre</strong></td>
									<td>{{ $requerimiento->cierre }}</td>
								</tr>
								@endif
								</tr>
								@if($requerimiento->rechazo != "" and $requerimiento->porcentajeEjecutado != 100)
								<tr>
									<td><strong>Motivo rechazo</strong></td>
									<td>{{ $requerimiento->rechazo }}</td>
								</tr>
								@endif														
							</table>
						</div>
					</div>
					{{-- AVANCES --}}
					@if($user[0]->nombre == "resolutor" or $user[0]->nombre == "supervisor")
					<div class="row">
						<div class="col-md-12">
							<h2>Avances</h2>
							@if($requerimiento->estado == 1 and $requerimiento->aprobacion == 3)
							@if($res->id == $requerimiento->resolutor)
								<form method='HEAD' action="{{ url('/requerimientos/'.$requerimiento->id.'/avances/nuevo') }}">
									<button type="submit" value="Ingresar" class="btn btn-primary" name="" style="cursor:pointer"><i class="fa fa-plus"></i> Ingresar</button>
								</form>
							@endif
							@endif
							<div class="table-responsive">
								<table class="table table-borderless table-striped table-hover table-sm">
									<thead>
										<tr>
											<th>Fecha Avance</th>
											<th>Texto del Avance</th>
											@if($requerimiento->estado == 1 and $requerimiento->aprobacion == 3)
											<th>Acciones</th>
											@endif
										</tr>		
									</thead>
									<tbody>
									@forelse ($avances as $avance)	
									<tr>
										@if ($avance->idRequerimiento == $requerimiento->id)
										<td>
											{{ $avance->created_at->format('d-m-Y') }}
										</td>
										<td>{{ $avance->textAvance }}</td>
										@if($requerimiento->estado == 1 and $requerimiento->aprobacion == 3)
										<td>			
											<div scope="row" class="btn-group">
											<form method='HEAD' action="{{ url('/requerimientos/'.$requerimiento->id.'/avances/'.$avance->id.'/editar') }}">
												{{ csrf_field() }}
												<button type="submit" class="btn btn-primary btn-sm" data-toggle="tooltip" data-original-title="Editar" style="cursor:pointer"><i class="fa fa-pencil"></i></button>
											</form>
											&nbsp;&nbsp;&nbsp;
											<form method='POST' action="{{ url('/requerimientos/'.$requerimiento->id.'/avances/'.$avance->id) }}">
												{{ csrf_field() }}
												{{ method_field('DELETE') }}
												<button type="submit" class="btn btn-danger btn-sm" data-toggle="tooltip" data-original-title="Eliminar" style="cursor:pointer"><i class="fa fa-trash"></i></button>
											</form>	
											</div>					
										</td>
										@endif			
										@endif
									</tr>
									@empty
									@endforelse 
									</tbody>
								</table>
								{!! $avances->render() !!}	
							</div>
						</div>
					</div>
					@endif
					@if($user[0]->nombre == "administrador")
					<div class="row">
						<div class="col-md-12">
							<h2>Avances</h2>
							@if($requerimiento->estado == 1 and $requerimiento->aprobacion == 3)
								<form method='HEAD' action="{{ url('/requerimientos/'.$requerimiento->id.'/avances/nuevo') }}">
									<button type="submit" value="Ingresar" class="btn btn-primary" name="" style="cursor:pointer"><i class="fa fa-plus"></i> Ingresar</button>
								</form>
							@endif
							<div class="table-responsive">
								<table class="table table-borderless table-striped table-hover table-sm">
									<thead>
										<tr>
											<th>Fecha Avance</th>
											<th>Texto del Avance</th>
											@if($requerimiento->estado == 1 and $requerimiento->aprobacion == 3)
											<th>Acciones</th>
											@endif
										</tr>		
									</thead>
									<tbody>
									@forelse ($avances as $avance)	
									<tr>
										@if ($avance->idRequerimiento == $requerimiento->id)
										<td>
											{{ $avance->created_at->format('d-m-Y') }}
										</td>
										<td>{{ $avance->textAvance }}</td>
										@if($requerimiento->estado == 1 and $requerimiento->aprobacion == 3)
										<td>			
											<div scope="row" class="btn-group">
											<form method='HEAD' action="{{ url('/requerimientos/'.$requerimiento->id.'/avances/'.$avance->id.'/editar') }}">
												{{ csrf_field() }}
												<button type="submit" class="btn btn-primary btn-sm" data-toggle="tooltip" data-original-title="Editar" style="cursor:pointer"><i class="fa fa-pencil"></i></button>
											</form>
											&nbsp;&nbsp;&nbsp;
											<form method='POST' action="{{ url('/requerimientos/'.$requerimiento->id.'/avances/'.$avance->id) }}">
												{{ csrf_field() }}
												{{ method_field('DELETE') }}
												<button type="submit" class="btn btn-danger btn-sm" data-toggle="tooltip" data-original-title="Eliminar" style="cursor:pointer"><i class="fa fa-trash"></i></button>
											</form>	
											</div>					
										</td>
										@endif			
										@endif
									</tr>
									@empty
									@endforelse 
									</tbody>
								</table>
								{!! $avances->render() !!}	
							</div>
						</div>
					</div>
					@endif					
					{{-- TAREAS --}}
					@if($user[0]->nombre == "solicitante" or $user[0]->nombre == "administrador")
					<div class="row">
						<div class="col-md-12">
						<h2>Tareas</h2>
						@if($requerimiento->estado == 1 and $requerimiento->aprobacion == 3)
						<form method='HEAD' action="{{ url('/requerimientos/'.$requerimiento->id.'/tareas/nueva') }}">
							<button type="submit" value="Ingresar" class="btn btn-primary" name="" style="cursor:pointer"><i class="fa fa-plus"></i> Ingresar</button>
						</form>
						@endif
						<div class="table-responsive">
							<table class="table table-striped table-hover table-sm">
								<thead>
									<tr>
										<th>N°</th>
										<th>Tarea</th>
										<th>Solicitud</th>
										<th>Cierre</th>
										<th>Resolutor</th>
										<th>Estado</th>
										<th>Acciones</th>
									</tr>		
								</thead>
								<tbody>
									@forelse ($tareas as $tarea)	
									<tr>
										@if($tarea->estado == 1 or $tarea->estado == 2)
											<td>{{ $tarea->id2 }}</td>	
											<td>{{ $tarea->textoTarea }}</td>
											<td>{{date('d-m-Y', strtotime($tarea->fechaSolicitud)) }}</td>
											<td>{{date('d-m-Y', strtotime($tarea->fechaCierre)) }}</td>
										@endif
										@if($tarea->estado == 1 or $tarea->estado == 2)				
											<td>
											@forelse($resolutores as $resolutor)	
												{{ $resolutor->nombreResolutor }}
											@empty
											@endforelse	
											</td>
											<td>			
												@if($tarea->estado == 1)
												<span class="badge badge-default">Pendiente <i class="fa fa-circle text-warning"></i></span>
												@else
												<span class="badge badge-default">Completada <i class="fa fa-circle text-success"></i></span>
												@endif	
											</td>
											<td>
											<div scope="row" class="btn-group">
											@if($tarea->estado == 1)
												<form method='GET' action="{{ url("/requerimientos/{$requerimiento->id}/tareas/{$tarea->id}/terminar")}}">					
													{{ csrf_field() }}
													<button type="submit" class="btn btn-success btn-sm" data-toggle="tooltip" data-original-title="Terminar" style="cursor:pointer"><i class="fa fa-check"></i></button>
													<input type="hidden" name="tarea" value={{$tarea->id}}>
													<input type="hidden" name="req" value="{{ $requerimiento->id }}">
												</form>
											@endif
											&nbsp;&nbsp;
											@if($tarea->estado == 1)									
												<form method='HEAD' action="{{ url('/requerimientos/'.$requerimiento->id.'/tareas/'.$tarea->id.'/editar') }}">
													{{ csrf_field() }}
													<button type="submit" class="btn btn-primary btn-sm" data-toggle="tooltip" data-original-title="Editar" style="cursor:pointer"><i class="fa fa-pencil"></i></button>
												</form>
											@endif
											&nbsp;&nbsp;
											@if($tarea->estado == 1)							
												<form method='POST' action="{{ url('/requerimientos/'.$requerimiento->id.'/tareas/'.$tarea->id.'/eliminar') }}">
													{{ csrf_field() }}
													{{ method_field('DELETE') }}						
													<button type="submit" class="btn btn-danger btn-sm" data-toggle="tooltip" data-original-title="Eliminar" style="cursor:pointer"><i class="fa fa-trash"></i></button>
													<input type="hidden" name="tarea" value={{$tarea->id}}>
													<input type="hidden" name="req" value={{$requerimiento->id}}>
												</form>
											@endif
											</div>
											</td>
										@endif	
									</tr>						
									@empty
									@endforelse 
								</tbody>	
							</table>
						</div>
					</div>
				</div>
				@endif
					@if($user[0]->nombre == "resolutor" and $lider == 1)
					<div class="row">
						<div class="col-md-12">
						<h2>Tareas</h2>
						@if($requerimiento->estado == 1 and $requerimiento->aprobacion == 3)
						<form method='HEAD' action="{{ url('/requerimientos/'.$requerimiento->id.'/tareas/nueva') }}">
							<button type="submit" value="Ingresar" class="btn btn-primary" name="" style="cursor:pointer"><i class="fa fa-plus"></i> Ingresar</button>
						</form>
						@endif
						<div class="table-responsive">
							<table class="table table-striped table-hover table-sm">
								<thead>
									<tr>
										<th>N°</th>
										<th>Tarea</th>
										<th>Solicitud</th>
										<th>Cierre</th>
										<th>Resolutor</th>
										<th>Estado</th>
										<th>Acciones</th>
									</tr>		
								</thead>
								<tbody>
									@forelse ($tareas as $tarea)	
									<tr>
										@if($tarea->estado == 1 or $tarea->estado == 2)
											<td>{{ $tarea->id2 }}</td>	
											<td>{{ $tarea->textoTarea }}</td>
											<td>{{date('d-m-Y', strtotime($tarea->fechaSolicitud)) }}</td>
											<td>{{date('d-m-Y', strtotime($tarea->fechaCierre)) }}</td>
										@endif
										@if($tarea->estado == 1 or $tarea->estado == 2)				
											<td>
											@forelse($resolutores as $resolutor)	
												{{ $resolutor->nombreResolutor }}
											@empty
											@endforelse	
											</td>
											<td>			
												@if($tarea->estado == 1)
												<span class="badge badge-default">Pendiente <i class="fa fa-circle text-warning"></i></span>
												@else
												<span class="badge badge-default">Completada <i class="fa fa-circle text-success"></i></span>
												@endif	
											</td>
											<td>
											<div scope="row" class="btn-group">
											@if($tarea->estado == 1)
												<form method='GET' action="{{ url("/requerimientos/{$requerimiento->id}/tareas/{$tarea->id}/terminar")}}">					
													{{ csrf_field() }}
													<button type="submit" class="btn btn-success btn-sm" data-toggle="tooltip" data-original-title="Terminar" style="cursor:pointer"><i class="fa fa-check"></i></button>
													<input type="hidden" name="tarea" value={{$tarea->id}}>
													<input type="hidden" name="req" value="{{ $requerimiento->id }}">
												</form>
											@endif
											&nbsp;&nbsp;
											@if($tarea->estado == 1)									
												<form method='HEAD' action="{{ url('/requerimientos/'.$requerimiento->id.'/tareas/'.$tarea->id.'/editar') }}">
													{{ csrf_field() }}
													<button type="submit" class="btn btn-primary btn-sm" data-toggle="tooltip" data-original-title="Editar" style="cursor:pointer"><i class="fa fa-pencil"></i></button>
												</form>
											@endif
											&nbsp;&nbsp;
											@if($tarea->estado == 1)							
												<form method='POST' action="{{ url('/requerimientos/'.$requerimiento->id.'/tareas/'.$tarea->id.'/eliminar') }}">
													{{ csrf_field() }}
													{{ method_field('DELETE') }}						
													<button type="submit" class="btn btn-danger btn-sm" data-toggle="tooltip" data-original-title="Eliminar" style="cursor:pointer"><i class="fa fa-trash"></i></button>
													<input type="hidden" name="tarea" value={{$tarea->id}}>
													<input type="hidden" name="req" value={{$requerimiento->id}}>
												</form>
											@endif
											</div>
											</td>
										@endif	
									</tr>						
									@empty
									@endforelse 
								</tbody>	
							</table>
						</div>
					</div>
				</div>
				@endif				
				<br>
				<p><a href="{{url('requerimientos')}}" class="btn btn-outline-primary"><i class="fa fa-arrow-left"></i> Regresar al listado</a></p>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('script')
<script type="text/javascript">
    $(document).ready(function(){
        menu_activo('mRequerimientos');
		if (window.innerWidth < 768) {
			$('.btn').addClass('btn-sm');
		}
    });
</script>
@endsection