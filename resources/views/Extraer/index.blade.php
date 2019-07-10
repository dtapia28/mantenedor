@extends('Bases.dashboard')
@section('titulo', "Extracciones")
@section('contenido')
<link rel="stylesheet" type="text/css" href="{{ asset('css/index_style.css') }}">
<h2 style="padding-bottom: 40px" align="center">Exportar requerimientos</h2>
<div id="body">
<div id="porEstado" class="from-row">
	<h5>Por estado:</h5>
	<form method="GET" action="{{ url('/extracciones/estado') }}">
		<select id="porEstado" class="form-control col-md-8" name="estado">
			<option selected="selected" disabled="disabled">Escoge una opción</option>
			<option value="0">Inactivo</option>
			<option value="1">Activo</option>
	    </select>	
		<button id="btn1" class="btn btn-primary" type="submit">Extraer</button>	    	
	</form>
</div>
<div id="porEjecutado" class="from-row">
	<h5>Por porcentaje ejecutado:</h5>
	<form method="GET" action="{{ url('/extracciones/ejecutado') }}">
		<select id="comparacion" class="form-control col-md-7" name="comparacion">
			<option selected="selected" disabled="disabled">Escoger una opción</option>
			<option value="1">Menor o igual que</option>
			<option value="2">Mayor que</option>
	    </select>
	    <div>
	    <label for="porcentaje">Ingresa porcentaje de requerimiento(s):</label>
	    <input class="form-control col-md-3" type="number" name="porcentaje">	
	    </div>
		<button class="btn btn-primary" type="submit">Extraer</button>	    	
	</form>
</div>
<div id="porEjecutado" class="from-row">
	<h5>Por número de cambios:</h5>
	<form method="GET" action="{{ url('/extracciones/cambios') }}">
		<select id="comparacion" class="form-control col-md-7" name="comparacion">
			<option selected="selected" disabled="disabled">Escoge una opción</option>
			<option value="1">Menor o igual que</option>
			<option value="2">Mayor que</option>
	    </select>
	    <div>
	    <label for="porcentaje">Ingresa n° de cambios:</label>
	    <input class="form-control col-md-2" type="number" name="cambios">	
	    </div>
		<button class="btn btn-primary" type="submit">Extraer</button>	    	
	</form>
</div>
</div>
<div id="body2">
	<div id="porSolicitante" class="from-row">
		<h5>Por solicitante:</h5>
		<form method="GET" action="{{ url('/extracciones/solicitantes') }}">
        	<select class="form-control col-md-8" name="idSolicitante">
				<option selected="selected" disabled="disabled">Escoge una opción</option>
            	@foreach($solicitantes as $solicitante)
                	<optgroup>
                    	<option value={{ $solicitante->id }}>{{ $solicitante->nombreSolicitante }}</option>
                    </optgroup>
                @endforeach
            </select>
			<button class="btn btn-primary" type="submit">Extraer</button>            			
		</form>
	</div>
	<div id="porResolutor" class="from-row">
		<h5>Por resolutor:</h5>
		<form method="GET" action="{{ url('/extracciones/resolutores') }}">
			<select class="form-control col-md-8" name="idResolutor">
				<option selected="selected" disabled="disabled">Escoge una opción</option>
				@foreach($resolutors as $resolutor)
					<optgroup>
						<option value="{{ $resolutor->id }}">{{ $resolutor->nombreResolutor }}</option>
					</optgroup>
				@endforeach
			</select>
			<button class="btn btn-primary" type="submit">Extraer</button>
		</form>
	</div>
</div>
@endsection