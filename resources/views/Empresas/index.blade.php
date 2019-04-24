@extends('Bases.base')
@section('titulo', 'Empresas')
@section('contenido')
	<h1>Listado de Empresas</h1>
	<p>
	</p>
	<form method='HEAD' action="{{ url('empresas/nueva') }}">
	<button type="submit" value="Nueva Empresa" class="btn btn-primary" name="">Nueva Empresa</button>
	</form>
	<br>
	<tr>
	<table class="table table-striped stacktable">
		<thead>
		    <th scope="col"><strong>ID</strong></th>
		    <th scope="col"><strong>Nombre</strong></th>
		    <th scope="col"><strong>Editar</strong></th>
		    <th scope="col"><strong>Eliminar</strong></th>
		</thead>
		<tbody>
			@forelse ($empresas as $empresa)
				<tr>
				<th scope="row">
					{{ $empresa->id }}
				</th>
				<td>
					<a href="/empresas/{{ $empresa->id }}">									
						{{ $empresa->nombreEmpresa }}
					</a>
				</td>
				<td>									
					<form method='HEAD' action="/empresas/{{$empresa->id}}/editar">
						{{ csrf_field() }}
						<button type="submit" value="Editar" class="btn btn-info" name="">Editar</button>
					</form>
				</td>
				<td>
					<form method='POST' action="/empresas/{{$empresa->id}}">
						{{ csrf_field() }}
						{{ method_field('DELETE') }}						
						<button type="submit" value="Eliminar" class="btn btn-danger" name="">Eliminar</button>
					</form>
				</td>								
			@empty
				<li>No hay empresas registradas</li>	
			@endforelse
				</tr>
		</tbody>		
	</table>
@endsection	