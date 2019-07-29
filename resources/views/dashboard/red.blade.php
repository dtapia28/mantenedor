@extends('Bases.dashboard')
@section('contenido')
        <div class="card mb-3">
          <div class="card-header">
            <i class="fas fa-table"></i>
            Requerimientos vencidos</div>
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
                  </tr>
                </thead>
                <tbody>
            @forelse ($requerimientosRed as $requerimiento)
              <tr>
              <th id="tabla" scope="row">
                <a href="../public/requerimientos/{{ $requerimiento->id }}">
                  {{ $requerimiento->id }}
                </a>            
              </th>
              <td width="350px" style="text-align:left;"> 
                {{ $requerimiento->textoRequerimiento }}
              </td>       
              <td style="text-align: center;">  
                {{ date('d-m-Y', strtotime($requerimiento->fechaSolicitud)) }}
              </td>
              @if($requerimiento->fechaRealCierre != "")
              <td width="100px" style="text-align: center;">  
                {{ date('d-m-Y', strtotime($requerimiento->fechaRealCierre)) }}
              </td>
              @else             
              <td width="100px" style="text-align: center;">  
                {{ date('d-m-Y', strtotime($requerimiento->fechaCierre)) }}
              </td>
              @endif
              <td width="100px" style="text-align: center">               
              @forelse ($resolutors as $resolutor)
                @if ($requerimiento->resolutor == $resolutor->id)     
                {{ $resolutor->nombreResolutor }}
                @endif
              @empty
              @endforelse 
              </td>
              @forelse($resolutors as $resolutor)
                @if($requerimiento->resolutor == $resolutor->id)  
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
              @empty
              @endforelse                  
                </tbody>
              </table>
            </div>
          </div>
        </div>
@endsection