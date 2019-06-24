	@extends('Bases.dashboard')
	@section('titulo', "Requerimientos")
	@section('encabezado1')
		<a href="/requerimientos">Requerimientos</a>
	@endsection
	@section('encabezado2')
	    <li class="breadcrumb-item active">requerimientos</li>
	@endsection
	@section('contenido')
		<header>
		<h1>Listado de Requerimientos</h1>	
		</header>
		<main>
			@can('ver')
			<div class="form-check form-check-inline">
				<form method='HEAD' action="{{ url('requerimientos/nuevo') }}">
					<button type="submit" value="Nuevo Requerimiento" class="btn btn-primary" name="">Nuevo Requerimiento</button>
				</form>
			</div>
			@endcan
			<div class="form-check form-check-inline">
				<form class="navbar-form navbar-left pull-right" method='GET' action="{{ url('requerimientos/') }}">
					<select class="custom-select" name="state">
						<option value="">Escoja una opción</option>
						<option value="0">Inactivo</option>
						<option value="1">Activo</option>			      	
					</select>
					<button class="btn btn-outline-success my-2 my-sm-0" type="submit">Filtrar</button>
				</form>
			</div>
			<div class="form-check form-check-inline">	
				<?php
					if ($valor == 1)
					    {echo "<h5>requerimientos activos:</h5>";}
					else {
					    echo "<h5>requerimientos inactivos:</h5>";
					    }
				?>
			</div>			
		<br>
	        <div class="card mb-3">
	          <div class="card-header">
	            <i class="fas fa-table"></i>
	            Requerimientos</div>
	          <div class="card-body">
	            <div class="table-responsive">
	              <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
	                <thead>
	                  <tr>
	                    <th>Id</th>
	                    <th>Requerimiento</th>
	                    <th>Fecha Solicitud</th>
	                    <th>Fecha Cierre</th>
	                    <th>Resolutor</th>
	                    <th>Team</th>
	                    <th>Porcentaje</th>
	                    <th></th>
	                    <th></th>
	                    <th></th>
	                    <th>Anidar</th>
	                    <th></th>
	                  </tr>
	                </thead>
	                <tfoot>
	                  <tr>
	                    <th>Id</th>
	                    <th>Requerimiento</th>
	                    <th>Fecha Solicitud</th>
	                    <th>Fecha Cierre</th>
	                    <th>Resolutor</th>
	                    <th>Team</th>
	                    <th>Porcentaje</th>
	                    <th></th>
	                    <th></th>
	                    <th></th>
	                    <th>Anidar</th>
	                    <th></th>            
	                  </tr>
	                </tfoot>
	                <tbody>
						@forelse ($requerimientos as $requerimiento)
							<tr>
							<th id="tabla" scope="row">
								<a href="../requerimientos/{{ $requerimiento->id }}">					
									{{ $requerimiento->id }}
								</a>						
							</th>
							<td style="text-align:left;">	
								{{ $requerimiento->textoRequerimiento }}
							</td>				
							<td style="text-align: center;">	
								{{ date('d-m-Y', strtotime($requerimiento->fechaSolicitud)) }}
							</td>
							<td style="text-align: center;">	
								{{ date('d-m-Y', strtotime($requerimiento->fechaCierre)) }}
							</td>
							@forelse ($resolutors as $resolutor)
								@if ($requerimiento->idResolutor == $resolutor->id)
							<td style="text-align: center">				
								{{ $resolutor->nombreResolutor }}
								@endif
							</td>
							@empty
							@endforelse	
							@forelse ($teams as $team)
								@forelse ($resolutors as $resolutor)
									@if ($requerimiento->idResolutor == $resolutor->id)
										@if ($resolutor->idTeam == $team->id)
											<td style="text-align: center">				
											{{ $team->nameTeam }}
										@endif
									@endif	
								@empty
								@endforelse	
							</td>
							@empty
							@endforelse
							@forelse ($requerimientos as $requerimiento)
								<td style="text-align: center">
									{{ $requerimiento->porcentajeEjecutado }}
								</td>
							@empty
							@endforelse
							<td>
					<form method='HEAD' action="/requerimientos/{{$requerimiento->id}}/terminado">
						{{ csrf_field() }}
						<input type="image" align="center" src="{{ asset('img/correcta-marca.png') }}" width="30" height="30">
					</form>
				</td>																
				<td>									
					<form method='HEAD' action="/requerimientos/{{$requerimiento->id}}/editar">
						{{ csrf_field() }}
						<input type="image" align="center" src="{{ asset('img/edit.png') }}" width="30" height="30">
					</form>
				</td>
				<td>									
					<form method='POST' action="/requerimientos/{{$requerimiento->id}}">
						{{ csrf_field() }}
						{{ method_field('DELETE') }}						
						<input type="image" align="center" src="{{ asset('img/delete.png') }}" width="30" height="30">
					</form>
				</td>
				<td>
					<form method="POST" action="{{ url('requerimientos/anidar') }}">
						{{ csrf_field() }}						
	               	 	<input type="number" name="anidar" class="form-control">
	               	 	<input type="hidden" name="requerimiento" value={{ $requerimiento->id }}>				
				</td>
				<td>
						<button type="submit" class="btn btn-success">Anidar</button>
					</form>
				</td>																                  
	                    @empty
	                    @endforelse
	                </tbody>
	              </table>
	            </div>
	          </div>
	          <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>
	        </div>												
		</main>
	@endsection	