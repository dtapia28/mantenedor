<div class="ibox">
    <div class="ibox-head">
        <div class="ibox-title">Reporte RQ</div>
	<div class="ibox-tools">
            <a class="ibox-collapse"><i class="fa fa-minus"></i></a>
	</div>        
    </div>
    <div class="ibox-body">
        <style>
            thead th, td {
                border: 1px solid black;
                text-align: center;
            }
            .head_table{
                width: 120rem;
                font-size: 2.5rem;
                text-align: center;
            }
            
            tbody th{
                border: 1px solid black;
                text-align: left;
            }
        </style>
        <table width=80%>
            <tr >
                <td class="head_table">Requerimientos al {{ $hoy }}</td>
            </tr>
            <table width=80%>
                <thead>
                    <tr>
                        <th width=10%>Área</th>
                        <th width=20%>Resolutor</th>
                        <th width=10%>Cantidad de RQ pendientes al {{$ayer}}</th>
                        <th width=10%>Vencidos</th>
                        <th width=10%>Cantidad de RQ generado el día de hoy {{ $hoy }}</th>
                        <th width=10%>Cantidad de RQ cerrado el  día de hoy {{ $hoy }}</th>
                        <th width=10%>Cantidad de RQ Pendientes al {{$hoy}}</th>
                        <th width=10%>Semáforo</th>
                    </tr>
                </thead>
                <tbody>
                    @for ($i=0; $i<count((array)$valores["resolutores_array"]); $i++)
                            <tr>
                                <td>{{$valores['equipos_array'][$i]}}</td>
                                <td>{{$valores['resolutores_array'][$i]}}</td>
                                <td style="text-align: center;">{{$valores['pendientes_resolutor'][$i]}}</td>
                                <td style="text-align: center;">{{$valores['vencidos'][$i]}}</td>
                                <td style="text-align: center;">{{$valores['creadoHoy_resolutor'][$i]}}</td>
                                <td style="text-align: center;">{{$valores['cerrados'][$i]}}</td>
                                <td style="text-align: center;">{{$valores['pendientes_resolutor_hoy'][$i]}}</td>
                                @if ($valores['vencidos'][$i] >= 1 and $valores['vencidos'][$i]<=3)
                                <td style="background-color: yellow"></td>
                                @elseif($valores['vencidos'][$i]>=4 and $valores['vencidos'][$i]<=6)
                                <td style="background-color: red"></td>
                                @elseif($valores['vencidos'][$i]>7)
                                <td style="background-color: purple">GRAVE</td>
                                @else
                                <td style="background-color: green"></td>
                                @endif                             
                            </tr>
                    @endfor        
                </tbody>
            </table>
        </table>    
    </div>
</div>

