@extends('Bases.dashboard')
@section('contenido')
	<h1>Detalle de solicitante:</h1>
	<h2>Solicitante n° {{ $solicitante->id }}</h2>

	<p>Nombre del solicitante: <strong>{{ $solicitante->nombreSolicitante }}</strong></p>
	<p>Creado el: <strong>{{ $solicitante->created_at->format('d-m-Y') }}</strong></p>

	<p>
		<a href="/solicitantes/">Volver al listado de Solicitantes</a>
    </p>
@endsection	