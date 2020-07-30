@extends('Bases.base')
@section('titulo', 'Avances requerimiento {{$requerimiento->id}}')
@section('contenido')
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
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
		    <th scope="col"><strong>Fecha Avance</strong></th>
		    <th scope="col"><strong>Texto Avance</strong></th>
		    <th scope="col"><strong>Editar</strong></th>
		    <th scope="col"><strong>Eliminar</strong></th>
		</thead>
		<tbody>
			@forelse ($avances as $avance)
				@if ($avance->idRequerimiento == $requerimiento->id)
				<tr>
						<th scope="row">
							{{ $avance->fechaAvance }}
						</th>
						<td>
							{{ $avance->textAvance }}
						</td>
						<td>									
							<form method='HEAD' action="/requerimientos/{{$requerimiento->id}}/editar">
								{{ csrf_field() }}
								<button type="submit" value="Editar" class="btn btn-info" name="">Editar</button>
							</form>
						</td>
						<td>									
							<form method='POST' action="/requerimientos/{{$requerimiento->id}}">
								{{ csrf_field() }}
								{{ method_field('DELETE') }}						
								<button type="submit" value="Eliminar" class="btn btn-danger" name="">Eliminar</button>
							</form>
						</td>											
					@endif
				@empty
					<p>Error</p>	
				@endforelse
				</tr>	
		</tbody>		
	</table>
@endsection	