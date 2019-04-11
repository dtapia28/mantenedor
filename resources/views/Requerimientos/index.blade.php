@extends('Bases.base')
@section('contenido')
	<h1>Listado de Requerimientos</h1>
	<p>
	</p>
	<form method='HEAD' action="{{ url('requerimientos/nuevo') }}">
	<input type="submit" value="Nuevo Requerimiento" class="btn btn-primary" name="">
	</form>
	<table class="table">
		<thead>
		    <th scope="col"><strong>ID</strong></th>
		    <th scope="col"><strong><strong>Fecha Solicitud</strong></th>
		    <th scope="col"><strong><strong>Fecha Cierre</strong></th>
		    <th scope="col"><strong>% Ejecutado</strong></th>
		   	<th scope="col"><strong>Actualizar</strong></th>
		    <th scope="col"><strong>Eliminar</strong></th>
	    </thead>
	    <tbody>
			@forelse ($requerimientos as $requerimiento)
			<tr>
			<th scope="row">
				<a href="requerimientos/{{ $requerimiento->id }}">					
					{{ $requerimiento->id }}
					</a>						
				</th>
				<td>	
					{{ $requerimiento->fechaSolicitud }}
				</td>
				<td>	
					{{ $requerimiento->fechaCierre }}
				</td>
				<td>	
					{{ $requerimiento->avanceEjecutado }}
				</td>								
				<td>									
					<button class="btn btn-info">Editar</button>
				</td>
				<td>									
					<button class="btn btn-danger">Eliminar</button>
				</td>								
			@empty
				<li>No hay requerimientos registrados</li>	
			@endforelse
			</tr>
		</tbody>
	</table>
@endsection	