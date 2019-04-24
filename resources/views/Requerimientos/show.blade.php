@extends('Bases.detalles')
@section('hojaEstilo', "{{ asset('css/style.css') }}")
@section('titulo', "Detalle de Requerimientos")
@section('tituloRequerimiento')
	<h1>Detalle de requerimiento:</h1>
@endsection
@section('requerimiento')
	<br>
	<h2>Requerimiento n° {{ $requerimiento->id }}</h2>
	<br>
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
@endsection 
@section('avances')
	<header><h1>Avances del requerimiento:</h1></header>
		<form method='HEAD' action="/requerimientos/{{$requerimiento->id}}/avances/nuevo">
			<button type="submit" value="Ingresar avance" class="btn btn-primary" name="">Ingresar avance</button>
		</form>		
		<br>
		@forelse ($avances as $avance)	
		<p>
			@if ($avance->idRequerimiento == $requerimiento->id)
				<strong>Avance del {{ $avance->created_at->format('d-m-Y') }}: </strong>{{ $avance->textAvance }}
			@endif
   		 </p>
		@empty
		@endforelse    
@endsection   
    @section('footerMain')
    <p>
    	<br>   	
    	<a href="/requerimientos/">Volver al listado de Requerimientos</a>
    </p>
    @endsection
