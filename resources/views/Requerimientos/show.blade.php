@extends('Bases.base')
@section('contenido')
	<h1>Detalle de requerimiento:</h1>
	<h2>requerimiento n° {{ $requerimiento->id }}</h2>

	<p><strong>Texto del requerimiento: </strong>{{ $requerimiento->textoRequerimiento }}</p>
	<p>
		@forelse ($priorities as $priority)
			@if ($priority->id == $requerimiento->idPrioridad)
				<strong>Prioridad: </strong>{{ $priority->namePriority }}
			@endif
		@empty
		@endforelse
    </p>	
	<p>
		@forelse ($resolutors as $resolutor)
			@if ($resolutor->id == $requerimiento->idResolutor)
				<strong>Resolutor: </strong>{{ $resolutor->nombreResolutor }}
			@endif
		@empty
		@endforelse
    </p>
    <p><strong>Fecha de cierre: </strong> {{ $requerimiento->fechaCierre }}</p>
    <p><strong>Número de cambios: </strong> {{ $requerimiento->numeroCambios }}</p>
    <p><strong>Porcentaje ejecutado: </strong> {{ $requerimiento->porcentajeEjecutado }}</p>
    <br>
    <p><a href="/requerimientos/">Volver al listado de Requerimientos</a></p>
@endsection	