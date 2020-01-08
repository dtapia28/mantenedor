@extends('Bases.dashboard')

@section('css')
	<link href="{{ asset('vendor/DataTables/datatables.min.css') }}" rel="stylesheet" />
@endsection

@section('titulo', "Prioridades")

@section('contenido')
<div class="page-heading">
	<h1 class="page-title"><i class="fa fa-sort-amount-desc"></i> Prioridades</h1>
</div>
<div class="page-content fade-in-up">
	<div class="ibox">
		<div class="ibox-head">
			<div class="ibox-title">Listado de Prioridades</div>
			@if($user[0]->nombre == "administrador")
				<div class="pull-right"><a class="btn btn-success" href="{{ url('priorities/nueva') }}" style="white-space: normal;"><i class="fa fa-plus"></i> Nuevo Registro</a></div>
			@endif
		</div>
		<div class="ibox-body">	
			<div class="table-responsive">
				<table class="table table-bordered table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
					<thead>
					<tr>
						<th><strong>Id</strong></th>
						<th><strong>Nombre</strong></th>
						@if($user[0]->nombre == "administrador")
						<th><strong>Acciones</strong></th>
						@endif
					</tr>
					</thead>
					<tbody>
						@forelse ($priorities as $priority)
						<tr>
							<td id="tabla" scope="row">
								<a href="{{ url("priorities/{$priority->id}") }}">					
									{{ $priority->id }}
								</a>						
							</td>
							<td style="text-align:left;">	
								{{ $priority->namePriority }}
							</td>
							@if($user[0]->nombre == "administrador")			
							<td scope="row" class="form-inline">									
								<form method='HEAD' action="{{ url("priorities/{$priority->id}/editar") }}">
									{{ csrf_field() }}
									<button type="submit" class="btn btn-primary btn-sm" data-toggle="tooltip" data-original-title="Editar" style="cursor:pointer"><i class="fa fa-pencil"></i></button>
								</form>
								&nbsp;&nbsp;
								<form method='POST' action="../public/priorities/{{$priority->id}}">
									{{ csrf_field() }}
									{{ method_field('DELETE') }}						
									<button type="submit" class="btn btn-danger btn-sm" data-toggle="tooltip" data-original-title="Eliminar" style="cursor:pointer"><i class="fa fa-trash"></i></button>
								</form>
							</td>
							@endif					                  
							@empty
						</tr>
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
	menu_activo('mPrioridades');
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
