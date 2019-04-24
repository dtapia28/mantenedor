@extends('Bases.base')
@section('titulo', "Listado Requerimientos")
@section('contenido')
	<header>
	<h1>Listado de Requerimientos</h1>
	</header>
	<main>
	<form method='HEAD' action="{{ url('requerimientos/nuevo') }}">
	<button type="submit" value="Nuevo Requerimiento" class="btn btn-primary" name="">Nuevo Requerimiento</button>
	</form>
	<br>
	<table id="tablaRequerimientos" class="table table-striped stacktable">
		<thead>
		    <th scope="col"><strong>ID</strong></th>
		    <th scope="col"><strong>Requerimiento</strong></th>
		    <th scope="col"><strong><strong>Fecha Solicitud</strong></th>
		    <th scope="col"><strong><strong>Fecha Cierre</strong></th>
		    <th scope="col"><strong>Resolutor</strong></th>
		    <th scope="col"><strong>Team</strong></th>
		   	<th scope="col"><strong></strong></th>
		   	<th scope="col"><strong></strong></th>		   	
		    <th scope="col"><strong></strong></th>
	    </thead>
	    <tbody>
			@forelse ($requerimientos as $requerimiento)
			<tr>
			<th id="tabla" scope="row">
				<a href="/requerimientos/{{ $requerimiento->id }}">					
					{{ $requerimiento->id }}
				</a>						
				</th>
				<td>	
					{{ $requerimiento->textoRequerimiento }}
				</td>				
				<td>	
					{{ $requerimiento->fechaSolicitud }}
				</td>
				<td>	
					{{ $requerimiento->fechaCierre }}
				</td>
				@forelse ($resolutors as $resolutor)
				<td style="text-align: center">
					@if ($requerimiento->idResolutor == $resolutor->id)	
					{{ $resolutor->nombreResolutor }}
					@endif
				@empty
				@endforelse	
				</td>	
				@forelse ($teams as $team)
				<td style="text-align: center">
					@if ($resolutor->idTeam == $team->id)	
					{{ $team->nameTeam }}
					@endif
				@empty
				@endforelse	
				</td>											
				<td>									
					<form method='HEAD' action="/requerimientos/{{$requerimiento->id}}/editar">
						{{ csrf_field() }}
						<input type="image" align="center" src="{{ asset('img/edit.png') }}" width="30" height="30">
					</form>
				</td>
				<td>
					<form method='PUT' action="/requerimientos/{{$requerimiento->id}}/actualizar">
						{{ csrf_field() }}
						<input type="image" align="center" src="{{ asset('img/update.png') }}" width="30" height="30">
					</form>
					
				</td>
				<td>									
					<form method='POST' action="/requerimientos/{{$requerimiento->id}}">
						{{ csrf_field() }}
						{{ method_field('DELETE') }}						
						<input type="image" align="center" src="{{ asset('img/delete.png') }}" width="30" height="30">
					</form>
				</td>								
			@empty
				<li>No hay requerimientos registrados</li>	
			@endforelse
			</tr>
		</tbody>
	</table>
	</main>
@endsection	