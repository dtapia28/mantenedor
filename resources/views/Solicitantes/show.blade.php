@extends('Bases.dashboard')
@section('titulo', 'Detalle Solicitante')

@section('contenido')
<div class="page-heading">
	<h1 class="page-title"><i class="fa fa-address-book-o"></i> Solicitantes</h1>
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><i class="fa fa-arrow-circle-right"></i> Detalle de Solicitante</li>
	</ol>
</div>
<div class="page-content fade-in-up">
	<div class="row">
		<div class="col-md-12">
			<div class="ibox">
				<div class="ibox-head">
					<div class="ibox-title">Datos del Solicitante</div>
				</div>
				<div class="ibox-body">
					<div class="col-md-6">
						<h2>Solicitante n° {{ $solicitante->id }}</h2>
						<br>
						<table class="table table-condensed">
							<tr>
								<td width="35%"><strong>Nombre del solicitante</strong></td>
								<td width="65%">{{ $solicitante->nombreSolicitante }}</td>
							</tr>
							<tr>
								<td><strong>Creado el</strong></td>
								<td>{{ $solicitante->created_at->format('d-m-Y') }}</td>
							</tr>
						</table>
						<br>
						<p><a href="{{url('solicitantes')}}" class="btn btn-outline-primary"><i class="fa fa-arrow-left"></i> Regresar al listado</a></p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
