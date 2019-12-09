@extends('Bases.dashboard')

@section('css')
	<link href="{{ asset('vendor/DataTables/datatables.min.css') }}" rel="stylesheet" />
@endsection

@section('titulo', 'Solicitantes')

@section('contenido')
@if(session()->has('msj'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('msj') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    </div>
@endif
<div class="page-heading">
	<h1 class="page-title"><i class="fa fa-address-book-o"></i> Solicitantes</h1>
</div>
<div class="page-content fade-in-up">
	<div class="ibox">
		<div class="ibox-head">
			<div class="ibox-title">Listado de Solicitantes</div>
			@if($user[0]->nombre == "administrador")
				<div class="d-flex align-content-end"><a class="btn btn-success" href="{{ url('users/nuevo') }}"><i class="fa fa-plus"></i> Nuevo Registro</a></div>
			@endif
		</div>
		<div class="ibox-body">
			<div class="table-responsive">
				<table class="table table-bordered table-striped table-hover" id="dataTable" width="100%"  cellspacing="0">
					<thead>
						<tr>
							<th scope="col"><strong>ID</strong></th>
							<th scope="col"><strong>Nombre</strong></th>
							@if($user[0]->nombre == "administrador")
							<th scope="col"><strong>Acciones</strong></th>
							@endif
						</tr>
					</thead>
					<tbody>
					@forelse ($solicitantes as $solicitante)
					<tr>
						<td scope="row">
							<a href="../public/solicitantes/{{ $solicitante->id }}">														    
							{{ $solicitante->id }}
							</a>
						</td>
						<td scope="row">
							{{ $solicitante->nombreSolicitante }}
						</td>
						@if($user[0]->nombre == "administrador")
						<td scope="row" class="form-inline">
							<form method='post' action="{{ url('solicitantes/'.$solicitante->id)}}">
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
	menu_activo('mSolicitantes');
	$(function() {
		$('#dataTable').DataTable({
			"language": {
				"url": "{{ asset('vendor/DataTables/lang/spanish.json') }}"
			},
			pageLength: 10,
			
		});
	})
</script>
@endsection
