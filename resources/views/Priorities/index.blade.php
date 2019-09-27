@extends('Bases.dashboard')
@section('titulo', "Prioridades")
@section('contenido')
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
 <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
	<h1>Listado de Prioridades</h1>
	<p>
	</p>
	@if($user[0]->nombre == "administrador")
	<form method='HEAD' action="{{ url('priorities/nueva') }}">
	<button type="submit" value="Nueva Prioridad" class="btn btn-primary" name="">Nueva</button>
	</form>
	@endif
	<br>
        <div class="card mb-3">
          <div class="card-header">
            <i class="fas fa-table"></i>
            Prioridades</div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    @if($user[0]->nombre == "administrador")
                    <th></th>
                    @endif
                    @if($user[0]->nombre == "administrador")
                    <th></th>
                    @endif
                  </tr>
                </thead>
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
						@if($user[0]->nombre == "administrador")			
						<td>									
							<form method='HEAD' action="../public/priorities/{{$priority->id}}/editar">
								{{ csrf_field() }}
								<input type="image" align="center" src="{{ asset('img/edit.png') }}" width="30" height="30">
							</form>
						</td>
						@endif
						@if($user[0]->nombre == "administrador")
						<td>									
							<form method='POST' action="../public/priorities/{{$priority->id}}">
								{{ csrf_field() }}
								{{ method_field('DELETE') }}						
								<input type="image" align="center" src="{{ asset('img/delete.png') }}" width="30" height="30">
							</form>
						</td>
						@endif					                  
					@empty
					@endforelse
					</tbody>
				</table>
			</div>
		</div>
@endsection	