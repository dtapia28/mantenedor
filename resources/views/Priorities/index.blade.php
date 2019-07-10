@extends('Bases.dashboard')
@section('titulo', "Prioridades")
@section('contenido')
	<h1>Listado de Prioridades</h1>
	<p>
	</p>
	<form method='HEAD' action="{{ url('priorities/nueva') }}">
	<button type="submit" value="Nueva Prioridad" class="btn btn-primary" name="">Nueva</button>
	</form>
	<br>
        <div class="card mb-3">
          <div class="card-header">
            <i class="fas fa-table"></i>
            Prioridades</div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th></th>
                    <th></th>
                  </tr>
                </thead>
                <tfoot>
                  <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th></th>
                    <th></th>                    
                  </tr>
                </tfoot>
                <tbody>
					@forelse ($priorities as $priority)
						<tr>
						<th id="tabla" scope="row">
							<a href="/priorities/{{ $priority->id }}">					
								{{ $priority->id }}
							</a>						
						</th>
						<td style="text-align:left;">	
							{{ $priority->namePriority }}
						</td>																				
						<td>									
							<form method='HEAD' action="/priorities/{{$priority->id}}/editar">
								{{ csrf_field() }}
								<input type="image" align="center" src="{{ asset('img/edit.png') }}" width="30" height="30">
							</form>
						</td>
						<td>									
							<form method='POST' action="/priorities/{{$priority->id}}">
								{{ csrf_field() }}
								{{ method_field('DELETE') }}						
								<input type="image" align="center" src="{{ asset('img/delete.png') }}" width="30" height="30">
							</form>
						</td>														                  
					@empty
					@endforelse
					</tbody>
				</table>
			</div>
		</div>
		<div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>
@endsection	