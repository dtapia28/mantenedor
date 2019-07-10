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
		    <th width="50px" scope="col"><strong>ID</strong></th>
		    <th width="30px" scope="col"><strong>Nombre</strong></th>
		    <th width="50px" scope="col"><strong></strong></th>
		    <th width="50px;" scope="col"><strong></strong></th>
		</thead>
		<tbody>
			@forelse ($empresas as $empresa)
				<tr>
				<th scope="row">
					{{ $empresa->id }}
				</th>
				<td>
					<a href="../public/empresas/{{ $empresa->id }}">									
						{{ $empresa->nombreEmpresa }}
					</a>
				</td>
				<td>									
					<form method='HEAD' action="empresas/{{$empresa->id}}/editar">
						{{ csrf_field() }}
						<input style="text-align: center;" type="image" align="center" src="{{ asset('img/edit.png') }}" width="30" height="30">
					</form>
				</td>
				<td>
					<form method='POST' action="empresas/{{$empresa->id}}">
						{{ csrf_field() }}
						{{ method_field('DELETE') }}						
						<input type="image" align="center" src="{{ asset('img/delete.png') }}" width="30" height="30">
					</form>
				</td>								
			@empty
				<li>No hay empresas registradas</li>	
			@endforelse
				</tr>
		</tbody>		
	</table>
	{{ $empresas->links() }}	
@endsection	