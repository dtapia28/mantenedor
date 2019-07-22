	@extends('Bases.dashboard')
	@section('titulo', "Requerimientos")
	@section('contenido')
	  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
	 <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
		<header><meta http-equiv="Content-Type" content="text/html; charset=gb18030">
		<h1>Listado de Requerimientos</h1>	
		</header>
		<main>
			<div class="form-check form-check-inline">
				<form method='HEAD' action="{{ url('requerimientos/nuevo') }}">
					<button type="submit" value="Nuevo Requerimiento" class="btn btn-primary" name="">Nuevo</button>
				</form>
			</div>
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
					    {echo "<h5>Activos:</h5>";}
					else {
					    echo "<h5>Inactivos:</h5>";
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
	              <table class="table table-bordered table-striped table-hover" id="dataTable" width="100%"  cellspacing="0">
	                <thead>
	                  <tr>
	                    <th>Id</th>
	                    <th>Requerimiento</th>
	                    <th>Fecha Solicitud</th>
	                    <th>Fecha Cierre</th>
	                    <th>Ejecutado (%)</th>
	                    <th>Resolutor</th>
	                    <th>Team</th>
	                    <th></th>
	                    <th></th>
	                    <th></th>
	                    <th>Anidar</th>
	                    <th></th>
	                  </tr>
	                </thead>
	                <tbody>
						@forelse ($requerimientos as $requerimiento)
							<tr>
							<th id="tabla" scope="row">
								<a href="../public/requerimientos/{{ $requerimiento->id }}">
									{{ $requerimiento->id }}
								</a>						
							</th>
							<td width="350px" style="text-align:left;">	
								{{ $requerimiento->textoRequerimiento }}
							</td>				
							<td style="text-align: center;">	
								{{ date('d-m-Y', strtotime($requerimiento->fechaSolicitud)) }}
							</td>
							@if($requerimiento->fechaRealCierre != "")
							<td width="100px" style="text-align: center;">	
								{{ date('d-m-Y', strtotime($requerimiento->fechaRealCierre)) }}
							</td>
							@else							
							<td width="100px" style="text-align: center;">	
								{{ date('d-m-Y', strtotime($requerimiento->fechaCierre)) }}
							</td>
							@endif
							<td style="text-align: center;">
								{{ $requerimiento->porcentajeEjecutado }}
							</td>
							<td width="100px" style="text-align: center">								
							@forelse ($resolutors as $resolutor)
								@if ($requerimiento->resolutor == $resolutor->id)			
								{{ $resolutor->nombreResolutor }}
								@endif
							@empty
							@endforelse	
							</td>
							@forelse($resolutors as $resolutor)
								@if($requerimiento->resolutor == $resolutor->id)	
									@forelse ($teams as $team)
										@if ($resolutor->idTeam == $team->id)
										<td style="text-align: center">				
										{{ $team->nameTeam }}
										@endif
									@empty
									@endforelse
								@endif	
							@empty
							@endforelse	
							</td>							
							<td>
					<form method='HEAD' action="../public/requerimientos/{{$requerimiento->id}}/terminado">
						{{ csrf_field() }}
						<input type="image" align="center" src="{{ asset('img/correcta-marca.png') }}" width="30" height="30">
					</form>
				</td>																
				<td>									
					<form method='HEAD' action="../public/requerimientos/{{$requerimiento->id}}/editar">
						{{ csrf_field() }}
						<input type="image" align="center" src="{{ asset('img/edit.png') }}" width="30" height="30">
					</form>
				</td>
				<td>									
					<form method='POST' action="../public/requerimientos/{{$requerimiento->id}}">
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
	        </div>												
		</main>
	@endsection	