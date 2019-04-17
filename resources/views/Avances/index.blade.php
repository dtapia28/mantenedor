@extends('Bases.base')
@section('titulo', 'Avances requerimiento {{$requerimiento->id}}')
@section('contenido')
	<h1>Avances del requerimiento n° {{$requerimiento->id}}</h1>
	<p>
	</p>
	<form method='HEAD' action="/requerimientos/{{$requerimiento->id}}/avances/nuevo">
	<button type="submit" value="Nuevo Requerimiento" class="btn btn-primary" name="">Nuevo Requerimiento</button>
	</form>
	<br>
	<tr>
	<table class="table table-striped">
		<thead>
		    <th scope="col"><strong>ID</strong></th>
		    <th scope="col"><strong>Nombre</strong></th>
		    <th scope="col"><strong>Editar</strong></th>
		    <th scope="col"><strong>Eliminar</strong></th>
		</thead>
		<tbody>
			@forelse ($avances as $avance)
				@if ($avance->idRequerimiento == $requerimiento->id)
					<th scope="row">
						{{ $avance->textAvance }}
					</th>	
				@endif
			@empty
				<p>Error</p>	
			@endforelse	
		</tbody>		
	</table>
@endsection	