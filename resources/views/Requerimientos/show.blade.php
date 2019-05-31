@extends('Bases.detalles')
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
		<strong>Fecha de email: </strong>{{date('d-m-Y', strtotime($requerimiento->fechaEmail)) }}
    </p>
	<p>
		<strong>Fecha de solicitud: </strong>{{date('d-m-Y', strtotime($requerimiento->fechaSolicitud)) }}
    </p>
	<p>
		@forelse ($teams as $team)
			@forelse ($resolutors as $resolutor)
				@if ($resolutor->idTeam == $team->id)
					@if ($resolutor->id == $requerimiento->idResolutor)
						<strong>Equipo: </strong>{{ $team->nameTeam }}
					@endif
				@endif
			@empty
			@endforelse
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
    <p><strong>Fecha de cierre: </strong> {{date('d-m-Y', strtotime($requerimiento->fechaCierre)) }}</p>
    <p>
    	@if ($requerimiento->fechaRealCierre != null)
    		<strong>Fecha real cierre: </strong> {{date('d-m-Y', strtotime($requerimiento->fechaRealCierre)) }}
    	@else
    		<strong>Fecha real cierre: </strong>
    	@endif
    </p>
    <p>
    	@if ($resolutor->fechaRealCierre != "")
    		<strong>Mes de cierre real: </strong> {{date('n', strtotime($requerimiento->fechaRealCierre)).strftime("%e") }}
    	@else
    		<strong>Mes de cierre: </strong>{{ date('n', strtotime($requerimiento->fechaCierre)) }}
    	@endif    	
    </p>		    
    <p><strong>Número de cambios: </strong> {{ $requerimiento->numeroCambios }}</p>
    <p>
    	@if ($requerimiento->numeroCambios <=1)
    		<strong>Status de cambio: </strong>V
    	@elseif ($requerimiento->numeroCambios <=3)
    		<strong>Status de cambio: </strong>A
    	@else
    		<strong>Status de cambio: </strong>R		
    	@endif
    </p>
    <p><strong>Días laborales: </strong> {{ $hastaCierre }}</p>
    <p><strong>Días transcurridos: </strong> {{ $hastaHoy }}</p>
    <p><strong>Días restantes: </strong> {{ $restantes }}</p>
    <p><strong>Días excedidos: </strong> {{ $hastaHoy-$hastaCierre }}</p>
    <p><strong>Avance diario: </strong>{{ number_format(100/$hastaCierre, 2, ',', '.') }}%</p>

    	@if ($fechaCierre<=$hoy)
    		    <p><strong>Avance esperado: </strong>100%</p>
    	@else
    		<p><strong>Avance esperado: </strong>{{ (100/$hastaCierre)*$hastaHoy }}</p>
    	@endif		      
    <p><strong>Porcentaje ejecutado: </strong> {{ $requerimiento->porcentajeEjecutado }}%</p>

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
				<strong>{{ $avance->created_at->format('d-m-Y') }}: </strong>{{ $avance->textAvance }}
			@endif
   		 </p>
		@empty
		@endforelse    
@endsection   
    @section('footerMain')
    @endsection
