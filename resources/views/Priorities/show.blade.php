@extends('Bases.dashboard')
@section('contenido')
	<h1>Detalle de prioridad:</h1>
	<h2>prioridad n° {{ $priority->id }}</h2>

	<p>Nombre de la prioridad: <strong>{{ $priority->namePriority }}</strong></p>

	<p>
		<a href="/priorities/">Volver al listado de prioridades</a>
    </p>
@endsection	