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
			@if($user[0]->nombre == "solicitante" or $user[0]->nombre == "administrador")
			<div class="form-check form-check-inline">
				<form method='HEAD' action="{{ url('requerimientos/nuevo') }}">
					<button type="submit" value="Nuevo Requerimiento" class="btn btn-primary" name="">Nuevo</button>
				</form>
			</div>
			@endif
			<div class="form-check form-check-inline">
				<form class="navbar-form navbar-left pull-right" method='GET' action="{{ url('requerimientos/') }}">
					<select id="state" class="custom-select" name="state">
						<option selected="true" disabled="disabled" value="">Escoja una opción</option>
						<option value="0">Inactivo</option>
						<option value="1">Activo</option>
						<option value="2">% mayor o igual que</option>
						<option value="3">% menor o igual que</option>			      	
					</select>
					<input class="form-control col-md-12" type="number" id="valor" style="display: none" name="valorN" placeholder="porcentaje avance">
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
	                    <th>Resolutor</th>
	                    <th>Status</th>
	                    <th>Ejecutado (%)</th>
	                    <th>Anidados</th>
	                    @if($user[0]->nombre == "resolutor" or $user[0]->nombre == "administrador")
	                    <th></th>
	                    @endif
	                    @if($user[0]->nombre == "solicitante" or $user[0]->nombre == "administrador")
	                    <th></th>
	                    @endif
	                    @if($user[0]->nombre == "solicitante" or $user[0]->nombre == "administrador")
	                    <th></th>
	                    @endif
	                  </tr>
	                </thead>
	                <tbody>
						@forelse ($requerimientos as $requerimiento)
							<tr>
							<th id="tabla" scope="row">
								<a href="{{ url("requerimientos/{$requerimiento->id}") }}">
									{{ $requerimiento->id2 }}
								</a>					
							</th>
							<td width="350px" style="text-align:left;">	
								{{ $requerimiento->textoRequerimiento }}
							</td>				
							<td style="text-align: center;">	
								{{ date('Y-m-d', strtotime($requerimiento->fechaSolicitud)) }}
							</td>
							@if($requerimiento->fechaRealCierre != "")
							<td width="100px" style="text-align: center;">	
								{{ date('Y-m-d', strtotime($requerimiento->fechaRealCierre)) }}
							</td>
							@else							
							<td width="200px" style="text-align: center;">	
								{{ date('Y-m-d', strtotime($requerimiento->fechaCierre)) }}
							</td>
							@endif
							<td width="100px" style="text-align: center">								
							@forelse ($resolutors as $resolutor)
								@if ($requerimiento->resolutor == $resolutor->id)			
								{{ $resolutor->nombreResolutor }}
								@endif
							@empty
							@endforelse	
							</td>
							<td>
							</td>
							<td style="text-align: center">
								{{ $requerimiento->porcentajeEjecutado }}
							</td>
							<td style="text-align: center">
								<?php
									$conteo = 0;
								 foreach ($anidados as $anidado) {
								 	if ($requerimiento->id == $anidado->idRequerimientoBase) {
								 		$conteo++;
								 	}
								 }
								 if ($conteo>0) {
								 	echo "Si";
								 } else {
								 	echo "No";
								 }
								?>
							</td>
					@if($user[0]->nombre == "resolutor" or $user[0]->nombre == "administrador")	
					<td>
					<form method='HEAD' action="{{ url("requerimientos/{$requerimiento->id}/terminado") }}">
						{{ csrf_field() }}
						<input type="image" align="center" src="{{ asset('img/correcta-marca.png') }}" width="30" height="30">
					</form>
					</td>
					@endif
					@if($user[0]->nombre == "solicitante" or $user[0]->nombre == "administrador")
				<td>									
					<form method='HEAD' action="{{ url("requerimientos/{$requerimiento->id}/editar") }}">
						{{ csrf_field() }}
						<input type="image" align="center" src="{{ asset('img/edit.png') }}" width="30" height="30">
					</form>
				</td>
				@endif
				@if($user[0]->nombre == "solicitante" or $user[0]->nombre == "administrador")
				<td>									
					<form method='POST' action="../public/requerimientos/{{$requerimiento->id}}">
						{{ csrf_field() }}
						{{ method_field('DELETE') }}						
						<input type="image" align="center" src="{{ asset('img/delete.png') }}" width="30" height="30">
					</form>
				</td>
				@endif									                  
	                    @empty
	                    @endforelse
	                </tbody>
	              </table>
	            </div>
	          </div>
	        </div>												
		</main>
<script type="text/javascript">
	$(document).ready(function(){
		$('#state').on('change', function(){
			var role = $(this).val();
			if(role == 2 || role ==3){
				document.getElementById("valor").style.display = "block";
			} else {
				document.getElementById("valor").style.display = "none";	
			}
		});
	});
</script>		
	@endsection	