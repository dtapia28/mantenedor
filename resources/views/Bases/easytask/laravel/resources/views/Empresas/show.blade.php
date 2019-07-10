@extends('Bases.base')
@section('contenido')
	<h1>Detalle de empresa:</h1>
	<h2>Empresa n° {{ $empresa->id }}</h2>

	<p>Nombre de la empresa: <strong>{{ $empresa->nombreEmpresa }}</strong></p>
	<p>Creado el: <strong>{{ $empresa->created_at->format('d-m-Y') }}</strong></p>

	<p>
		<a href="../empresas">Volver al listado de Empresas</a>
    </p>
@endsection	