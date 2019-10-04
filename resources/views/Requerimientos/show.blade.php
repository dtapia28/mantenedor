@extends('Bases.detalles2')
@section('titulo', "Detalle de Requerimientos")
@section('requerimiento')
	<h2>{{ $requerimiento->id2 }}</h2>
	<br>
	@if($user[0]->nombre == "resolutor")
	<p><strong>Comentario: </strong>{{ $requerimiento->comentario }}</p>
	@endif
	<p><strong>Solicitud: </strong>{{ $requerimiento->textoRequerimiento }}</p>
	<p>
		@forelse ($priorities as $priority)
			@if ($priority->id == $requerimiento->idPrioridad)
				<strong>Prioridad: </strong>{{ $priority->namePriority }}
			@endif
		@empty
		@endforelse
    </p>
	<p>
		<strong>Fecha original de requerimiento: </strong>{{date('d-m-Y', strtotime($requerimiento->fechaEmail)) }}
    </p>
	<p>
		<strong>Fecha de inicio seguimiento: </strong>{{date('d-m-Y', strtotime($requerimiento->fechaSolicitud))}}
    </p>
	<p>
		@forelse ($teams as $team)
			@forelse ($resolutors as $resolutor)
				@if ($resolutor->idTeam == $team->id)
					@if ($resolutor->id == $requerimiento->idResolutor)
						<strong>Equipo: </strong>{{ $team->nameTeam }}
					@endif
				@endif
			@empty
			@endforelse
		@empty
		@endforelse
    </p>           	
	<p>
		@forelse ($resolutors as $resolutor)
			@if ($resolutor->id == $requerimiento->idResolutor)
				<strong>Resolutor: </strong>{{ $resolutor->nombreResolutor }}
			@endif
		@empty
		@endforelse
    </p>
    <p><strong>Fecha solicitada de cierre: </strong> {{date('d-m-Y', strtotime($requerimiento->fechaCierre)) }}</p>
    <p>
    	@if ($requerimiento->fechaRealCierre != null)
    		<strong>Fecha real cierre: </strong> {{date('d-m-Y', strtotime($requerimiento->fechaRealCierre)) }}
    	@else
    		<strong>Fecha real cierre: </strong>
    	@endif
    </p>
    <p>
    	@if ($resolutor->fechaRealCierre != "")
    		<strong>Mes de cierre real: </strong> {{date('n', strtotime($requerimiento->fechaRealCierre)).strftime("%e") }}
    	@else
    		<strong>Mes de cierre: </strong>{{ date('n', strtotime($requerimiento->fechaCierre)) }}
    	@endif    	
    </p>		    
    <p><strong>Número de cambios: </strong> {{ $requerimiento->numeroCambios }}</p>
    <p>
    	@if ($requerimiento->numeroCambios <=1)
    		<strong>Status de cambio: </strong>V
    	@elseif ($requerimiento->numeroCambios <=3)
    		<strong>Status de cambio: </strong>A
    	@else
    		<strong>Status de cambio: </strong>R		
    	@endif
    </p>
    <p><strong>Días laborales: </strong> {{ $hastaCierre }}</p>
    <p><strong>Días transcurridos: </strong> {{ $hastaHoy }}</p>
    <p><strong>Días restantes: </strong> {{ $restantes }}</p>
    <p><strong>Días excedidos: </strong> {{ $excedidos }}</p>
    <p><strong>Avance diario: </strong>{{ number_format(100/$hastaCierre, 2, ',', '.') }}%</p>

    	@if ($fechaCierre<=$hoy)
    		    <p><strong>Avance esperado: </strong>100%</p>
    	@else
    		<p><strong>Avance esperado: </strong>{{ (100/$hastaCierre)*$hastaHoy }}%</p>
    	@endif		      
    <p><strong>Porcentaje ejecutado: </strong> {{ $requerimiento->porcentajeEjecutado }}%</p>
    @if($requerimiento->cierre != "")
    <p><strong>Cierre: </strong>{{ $requerimiento->cierre }}</p>
    @endif    

    <br>
@endsection 
@if($user[0]->nombre == "resolutor" or $user[0]->nombre == "administrador")
@section('avances')
	<header><h2>Avances:</h2></header>
		<form method='HEAD' action="../requerimientos/{{$requerimiento->id}}/avances/nuevo">
			<button type="submit" value="Ingresar" class="btn btn-primary" name="">Ingresar</button>
		</form>		
		<br>
		<table>
		<thead>
			<tr>
				<th></th>
				<th></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
		@forelse ($avances as $avance)	
			<tr>
			@if ($avance->idRequerimiento == $requerimiento->id)
				<th style="padding-right: 12px;">
				{{ $avance->created_at->format('d-m-Y') }}:{{ $avance->textAvance }}
				</th>
				<td style="padding-right: 12px;">
					<form method='HEAD' action="../requerimientos/{{$requerimiento->id}}/avances/{{ $avance->id }}/editar">
						{{ csrf_field() }}
						<input type="image" align="center" src="{{ asset('img/edit.png') }}" width="20" height="20">
					</form>
				</td>
				<td style="padding-right: 8px;">
					<form method='POST' action="../requerimientos/{{$requerimiento->id}}/avances/{{ $avance->id }}">
						{{ csrf_field() }}
						{{ method_field('DELETE') }}						
						<input type="image" align="center" src="{{ asset('img/delete.png') }}" width="20" height="20">
					</form>					
				</td>					
			@endif
   		 </p>
		@empty
		@endforelse 
		</tbody>
		</table>
@endsection
@endif
@if($user[0]->nombre == "resolutor" or $user[0]->nombre == "administrador")
@section('anidado')
	<header><h2>Anidados:</h2></header>
	<br>
	@forelse($requerimientosAnidados as $requerimientoB)
		<th>
			</strong><a href="../requerimientos/{{ $requerimientoB->id }}"><strong>{{ $requerimientoB->id2 }}</strong></a> {{ $requerimientoB->textoRequerimiento }}
		</th>
		<br>
	@empty
	@endforelse
	<form method="POST" action="../requerimientos/{{$requerimiento->id}}/anidar">
    	{{ csrf_field() }}		
        <div class="card mb-3">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>Id</th>
                    <th>Requerimiento</th>
                    <th>Fecha Solicitud</th>
                    <th>Fecha Cierre</th>
                    <th>Resolutor</th>
                    <th>Team</th>
                    <th>Anidar</th>
                  </tr>
                </thead>
                <tbody>
            @forelse ($requerimientos as $requerimientoA)
            @if($requerimientoA->id != $requerimiento->id)
	              <tr>
	              <th id="tabla" scope="row">
	                <a href="requerimientos/{{ $requerimientoA->id }}">
	                  {{ $requerimientoA->id2 }}
	                </a>            
	              </th>
	              <td width="350px" style="text-align:left;"> 
	                {{ $requerimientoA->textoRequerimiento }}
	              </td>       
	              <td style="text-align: center;">  
	                {{ date('d-m-Y', strtotime($requerimientoA->fechaSolicitud)) }}
	              </td>
	              @if($requerimiento->fechaRealCierre != "")
	              <td width="100px" style="text-align: center;">  
	                {{ date('d-m-Y', strtotime($requerimientoA->fechaRealCierre)) }}
	              </td>
	              @else             
	              <td width="100px" style="text-align: center;">  
	                {{ date('d-m-Y', strtotime($requerimientoA->fechaCierre)) }}
	              </td>
	              @endif
	              <td width="100px" style="text-align: center">               
	              @forelse ($resolutors as $resolutor)
	                @if ($requerimientoA->resolutor == $resolutor->id)     
	                {{ $resolutor->nombreResolutor }}
	                @endif
	              @empty
	              @endforelse 
	              </td>
	              @forelse($resolutors as $resolutor)
	                @if($requerimientoA->resolutor == $resolutor->id)  
	                  @forelse ($teams as $team)
	                    @if ($resolutor->idTeam == $team->id)
	                    <td style="text-align: center">       
	                    {{ $team->nameTeam }}
	                    @endif
	                  @empty
	                  @endforelse
	                @endif  
	              @empty
	              @endforelse 
	              </td>
	              <td style="text-align: center;">
	              	<input type="checkbox" name="requerimiento{{ $requerimientoA->id }}" value="{{ $requerimientoA->id }}">
	              </td>
	        @endif        
	        @empty
	        @endforelse              
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <input type="hidden" value="{{ $requerimiento->id }}" name="requerimiento">
	<button style="text-align: right;" type="submit" value="Ingresar" class="btn btn-primary" name="">Anidar</button>
</form>
@endsection
@endif
@if($user[0]->nombre == "solicitante" or $user[0]->nombre == "administrador")
@section('tareas')
<br>
<br>
<header><h2>Tareas:</h2></header>
<br>
<form method='HEAD' action="../requerimientos/{{$requerimiento->id}}/tareas/nueva">
	<button type="submit" value="Ingresar" class="btn btn-primary" name="">Ingresar</button>
</form>
<table>
	<thead>
		<tr>
			<th></th>
			<th style="text-align: center;">Tarea</th>
			<th style="text-align: center;">Solicitud</th>
			<th style="text-align: center;">Cierre</th>
			<th></th>
			<th></th>
		</tr>		
	</thead>
	<tbody>
		@forelse ($tareas as $tarea)	
			<tr>
				@if($tarea->estado == 1 or $tarea->estado == 2)
				<th style="padding-right: 12px;">
					{{ $tarea->id2 }}
				</th>	
				<th style="padding-right: 12px;">
				{{ $tarea->textoTarea }}
				</th>
				<td style="padding-right: 12px;">
					{{date('d-m-Y', strtotime($tarea->fechaSolicitud)) }}
				</td>
				<td style="padding-right: 8px;">
					{{date('d-m-Y', strtotime($tarea->fechaCierre)) }}							
				</td>
				<td>
				@if($tarea->estado == 1)
					Pendiente
				@else
					Completada	
				@endif	
				</td>
				@if($tarea->estado == 1)	
				<td style="padding: 8px;">
					<form method='GET' action="{{ url("/requerimientos/{$requerimiento->id}/tareas/{$tarea->id}/terminar")}}">					
						{{ csrf_field() }}
						<input type="image" align="center" src="{{ asset('img/correcta-marca.png') }}" width="15" height="15">
						<input type="hidden" name="tarea" value={{$tarea->id}}>
						<input type="hidden" name="req" value="{{ $requerimiento->id }}">
					</form>
				</td>
				@else
				<td>
				</td>
				@endif
				@if($tarea->estado == 1)			
				<td style="padding-right: 8px;">									
					<form method='HEAD' action="/requerimientos/{{$requerimiento->id}}/tareas/{{ $tarea->id }}/editar">
						{{ csrf_field() }}
						<input type="image" align="center" src="{{ asset('img/edit.png') }}" width="15" height="15">

					</form>
				</td>
				@else
				<td>
				</td>
				@endif
				@if($tarea->estado == 1)
				<td>									
					<form method='POST' action="/requerimientos/{{$requerimiento->id}}/tareas/{{ $tarea->id }}/eliminar">
						{{ csrf_field() }}
						{{ method_field('DELETE') }}						
						<input type="image" align="center" src="{{ asset('img/delete.png') }}" width="15" height="15">
						<input type="hidden" name="tarea" value={{$tarea->id}}>
						<input type="hidden" name="req" value={{$requerimiento->id}}>
					</form>
				</td>					
				@else
				<td>
				</td>
				@endif	
				@endif	
			</tr>						
   		 </p>
		@empty
		@endforelse 
		</tbody>	
</table>
@endsection
@endif
    @section('footerMain')
    @endsection
