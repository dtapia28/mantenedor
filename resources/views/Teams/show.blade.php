@extends('Bases.dashboard')
@section('titulo', "Detalle equipo")
@section('contenido')
	<h1>Detalle de equipo:</h1>
	<h2>Equipo {{ $team->id2 }}</h2>

	<p>Nombre del equipo: <strong>{{ $team->nameTeam }}</strong></p>
	<p>Creado el: <strong>{{ $team->created_at->format('d-m-Y') }}</strong></p>

	<p>
		<a href="{{url('teams')}}">Volver al listado de equipos</a>
    </p>
@endsection	