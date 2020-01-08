@extends('Bases.dashboard')
@section('titulo', "Anidar")

@section('contenido')
<div class="page-heading">
	<h1 class="page-title"><i class="fa fa-compress"></i> Anidar</h1>
</div>
<div class="page-content fade-in-up">
	<div class="ibox">
		<div class="ibox-head">
			<div class="ibox-title">Listado de Requerimientos</div>
		</div>
		<div class="ibox-body">	
			@if($user[0]->nombre == "resolutor" or $user[0]->nombre == "administrador")
			@forelse($requerimientosAnidados as $requerimientoB)
				<th>
					<a href="{{ url('requerimientos/'.$requerimientoB->id) }}"><strong>{{ $requerimientoB->id2 }}</strong></a> {{ $requerimientoB->textoRequerimiento }}
				</th>
				<br>
			@empty
			@endforelse
			<form method="POST" action="{{ url('/requerimientos/'.$requerimiento->id.'/anidar') }}">
				{{ csrf_field() }}		
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
							<th>Anidar</th>
						</tr>
						</thead>
						<tbody>
						@forelse ($requerimientos as $requerimientoA)
						@if($requerimientoA->id != $requerimiento->id)
							<tr>
							<th id="tabla" scope="row">
								<a href="requerimientos/{{ $requerimientoA->id }}">
								{{ $requerimientoA->id2 }}
								</a>            
							</th>
							<td width="350px" style="text-align:left;"> 
								{{ $requerimientoA->textoRequerimiento }}
							</td>       
							<td style="text-align: center;">  
								{{ date('d-m-Y', strtotime($requerimientoA->fechaSolicitud)) }}
							</td>
							@if($requerimiento->fechaRealCierre != "")
							<td width="100px" style="text-align: center;">  
								{{ date('d-m-Y', strtotime($requerimientoA->fechaRealCierre)) }}
							</td>
							@else             
							<td width="100px" style="text-align: center;">  
								{{ date('d-m-Y', strtotime($requerimientoA->fechaCierre)) }}
							</td>
							@endif
							<td width="100px" style="text-align: center">               
							@forelse ($resolutors as $resolutor)
								@if ($requerimientoA->resolutor == $resolutor->id)     
								{{ $resolutor->nombreResolutor }}
								@endif
							@empty
							@endforelse 
							</td>
							@forelse($resolutors as $resolutor)
								@if($requerimientoA->resolutor == $resolutor->id)  
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
							<td style="text-align: center;">
								<input type="checkbox" name="requerimiento{{ $requerimientoA->id }}" value="{{ $requerimientoA->id }}">
							</td>
						@endif        
						@empty
						@endforelse              
						</tbody>
					</table>
				</div>
				<input type="hidden" value="{{ $requerimiento->id }}" name="requerimiento">
				<div class="col-sm-12 form-group">
					<div class="col-md-12 form-inline">
						<div class="col-md-3">
							<a href="{{url('requerimientos')}}" class="btn btn-outline-primary btn-block"><i class="fa fa-arrow-left"></i> Regresar al listado</a>
						</div>
						<div class="col-md-6"></div>
						<div class="col-md-3">
							<button type="submit" class="btn btn-success btn-block mb-2 mr-sm-2 mb-sm-0" style="cursor:pointer"><i class="fa fa-compress"></i> Anidar Requerimientos</button>
						</div>
					</div>
				</div>
			</form>
			@endif
		</div>
	</div>
</div>
@endsection

@section('script')
<script type="text/javascript">
    $(document).ready(function(){
        menu_activo('mRequerimientos');
    });
</script>
@endsection
