@extends('Bases.dashboard')
@section('titulo', "Detalle team")

@section('contenido')
<div class="page-heading">
	<h1 class="page-title"><i class="fa fa-users"></i> Equipos</h1>
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><i class="fa fa-arrow-circle-right"></i> Detalle de Equipo</li>
	</ol>
</div>
<div class="page-content fade-in-up">
	<div class="row">
		<div class="col-md-12">
			<div class="ibox">
				<div class="ibox-head">
					<div class="ibox-title">Datos del Equipo</div>
				</div>
				<div class="ibox-body">
					<div class="col-md-6">
						<h2>Equipo n° {{ $team->id }}</h2>
						<br>
						<table class="table table-condensed">
							<tr>
								<td width="35%"><strong>Nombre del equipo</strong></td>
								<td width="65%">{{ $team->nameTeam }}</td>
							</tr>
							<tr>
								<td><strong>Creado el</strong></td>
								<td>{{ $team->created_at->format('d-m-Y') }}</td>
							</tr>
						</table>
						<br>
						<p><a href="{{url('teams')}}" class="btn btn-outline-primary"><i class="fa fa-arrow-left"></i> Regresar al listado</a></p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection	