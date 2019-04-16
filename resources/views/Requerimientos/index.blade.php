@extends('Bases.base')
@section('titulo', "Listado Requerimientos")
@section('contenido')
	<h1>Listado de Requerimientos</h1>
	<p>
	</p>
	<form method='HEAD' action="{{ url('requerimientos/nuevo') }}">
	<button type="submit" value="Nuevo Requerimiento" class="btn btn-primary" name="">Nuevo Requerimiento</button>
	</form>
	<table id="tablaRequerimiento" class="table">
		<thead>
		    <th scope="col"><strong>ID</strong></th>
		    <th scope="col"><strong><strong>Fecha Solicitud</strong></th>
		    <th scope="col"><strong><strong>Fecha Cierre</strong></th>
		    <th scope="col"><strong>% Ejecutado</strong></th>
		   	<th scope="col"><strong>Editar</strong></th>
		   	<th scope="col"><strong>Actualizar</strong></th>		   	
		    <th scope="col"><strong>Eliminar</strong></th>
	    </thead>
	    <tbody>
			@forelse ($requerimientos as $requerimiento)
			<tr>
			<th scope="row">
				<a href="/requerimientos/{{ $requerimiento->id }}">					
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
					<form method='HEAD' action="/requerimientos/{{$requerimiento->id}}/editar">
						{{ csrf_field() }}
						<button type="submit" value="Editar" class="btn btn-info" name="">Editar</button>
					</form>
				</td>
				<td>
					<form method='PUT' action="/requerimientos/{{$requerimiento->id}}/actualizar">
						{{ csrf_field() }}
						<button type="submit" class="btn btn-success">Actualizar</button>
					</form>
					
				</td>
				<td>									
					<form method='POST' action="/requerimientos/{{$requerimiento->id}}">
						{{ csrf_field() }}
						{{ method_field('DELETE') }}						
						<button type="submit" value="Eliminar" class="btn btn-danger" name="">Eliminar</button>
					</form>
				</td>								
			@empty
				<li>No hay requerimientos registrados</li>	
			@endforelse
			</tr>
		</tbody>
	</table>
@endsection	