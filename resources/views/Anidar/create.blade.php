@extends('Bases.detalles2')
@section('titulo', "Anidar")
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
	<form method="POST" action="{{ url('/requerimientos/'.$requerimiento->id.'/anidar') }}">
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