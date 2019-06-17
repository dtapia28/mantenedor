@extends('Bases.dashboard')
@section('contenido')
        <div class="card mb-3">
          <div class="card-header">
            <i class="fas fa-table"></i>
            Requerimientos al día</div>
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
                <tfoot>
                  <tr>
                    <th>Id</th>
                    <th>Requerimiento</th>
                    <th>Fecha Solicitud</th>
                    <th>Fecha Cierre</th>
                    <th>Resolutor</th>
                    <th>Team</th>
                  </tr>
                </tfoot>
                <tbody>
					@forelse ($requerimientosYellow as $requerimiento)
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
							{{ date('d-m-Y', strtotime($requerimiento->fechaSolicitud)) }}
						</td>
						<td style="text-align: center;">	
							{{ date('d-m-Y', strtotime($requerimiento->fechaCierre)) }}
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
                    @empty
                    @endforelse
                </tbody>
              </table>
            </div>
          </div>
          <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>
        </div>
@endsection