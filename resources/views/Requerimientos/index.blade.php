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
		    <th style="text-align: center;" width="700px" scope="col"><strong>Requerimiento</strong></th>
		    <th width="250px" scope="col"><strong><strong>Fecha Solicitud</strong></th>
		    <th width="230px" scope="col"><strong><strong>Fecha Cierre</strong></th>
		    <th scope="col"><strong>Resolutor</strong></th>
		    <th style="text-align: center;" width="250px" scope="col"><strong>Team</strong></th>
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
			<td style="text-align:left;">	
				{{ $requerimiento->textoRequerimiento }}
			</td>				
			<td style="text-align: center;">	
				{{ $requerimiento->fechaSolicitud }}
			</td>
			<td style="text-align: center;">	
				{{ $requerimiento->fechaCierre }}
			</td>
			@forelse ($resolutors as $resolutor)
				@if ($requerimiento->idResolutor == $resolutor->id)
			<td style="text-align: center">				
				{{ $resolutor->nombreResolutor }}
				@endif
			</td>
			@empty
			@endforelse	
			@forelse ($teams as $team)
				@if ($resolutor->idTeam == $team->id)
				<td style="text-align: center">				
				{{ $team->nameTeam }}
				@endif
			</td>
			@empty
			@endforelse	
			<td>									
				<form method='HEAD' action="/requerimientos/{{$requerimiento->id}}/editar">
					{{ csrf_field() }}
					<input type="image" align="center" src="{{ asset('img/correcta-marca.png') }}" width="30" height="30">
				</form>
			</td>																
			<td>									
				<form method='HEAD' action="/requerimientos/{{$requerimiento->id}}/editar">
					{{ csrf_field() }}
					<input type="image" align="center" src="{{ asset('img/edit.png') }}" width="30" height="30">
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
	{{ $requerimientos->links() }}
	</main>
@endsection	