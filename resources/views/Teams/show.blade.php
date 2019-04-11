@extends('Bases.base')
@section('contenido')
	<h1>Detalle de Teams:</h1>
	<h2>Team n° {{ $team->id }}</h2>

	<p>Nombre del team: <strong>{{ $team->nameTeam }}</strong></p>
	<p>Creado el: <strong>{{ $team->created_at->format('d-m-Y') }}</strong></p>

	<p>
		<a href="/teams/">Volver al listado de Teams</a>
    </p>
@endsection	