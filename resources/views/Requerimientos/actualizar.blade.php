@extends('Bases.base')
@section('titulo', "Actualizar requerimiento")
@section('contenido')
	<h1>Actualizar Requerimiento</h1>
	<br>
	<div class="p-4">
		<div class="form-group col-md-8">
		<form method='POST' action="{{ url("requerimientos/{$requerimiento->id}/save") }}">
			{{ method_field('PUT') }}
        	{{ csrf_field() }}
	        <label for='fechaCierre'>Fecha real de Cierre (no obligatoria):</label>
	        <input class="form-control col-md-3" value="{{ old('fechaRealCierre', $requerimiento->fechaRealCierre) }}" type="date" name="fechaRealCierre">
	        <br>
	        <label for="numeroCambios">Número de cambios:</label>
	        <input class="form-control col-md-2" value="{{ old('numeroCambios', $requerimiento->numeroCambios) }}" type="number" name="numeroCambios">
	        <br>
	        <label for="porcentajeEjecutado">Porcentaje ejecutado:</label>
	        <input class="form-control col-md-2" value="{{ old('porcentajeEjecutado', $requerimiento->porcentajeEjecutado) }}" type="number" name="porcentajeEjecutado">
	        <br>	
	        <label for="cierre">Cierre (no obligatorio):</label>
	        <br>
	        <textarea class="form-control col-md-7" name="cierre" placeholder="Cierre" rows="5" cols="50"></textarea>
	        <br>
            <button class="btn btn-primary" type="submit">Actualizar</button>
		</form>
		</div>
	</div>
@endsection