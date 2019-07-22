@extends('Bases.dashboard')
@section('contenido')
	<h1>Detalle de Resolutor:</h1>
	<h2>resolutor n° {{ $resolutor->id }}</h2>

	<p>Nombre de la prioridad: <strong>{{ $resolutor->nombreResolutor }}</strong></p>
	<p>Creado el: <strong>{{ $resolutor->created_at->format('d-m-Y') }}</strong></p>	

	<p>
		<a href="{{url('resolutors')}}">Volver al listado de Resolutores</a>
    </p>
@endsection	