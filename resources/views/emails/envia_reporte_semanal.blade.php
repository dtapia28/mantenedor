<div class="ibox">
    <div class="ibox-head">
        <div class="ibox-title">Reporte RQ</div>       
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
                        <th width=10%>&#193rea</th>
                        <th width=20%>Resolutor</th>
                        <th width=10%>Cantidad de RQ activos al {{$ayer}}</th>
                        <th width=10%>Vencidos</th>
                        <th width=10%>Cantidad de RQ generado el d&#237a de hoy {{ $hoy }}</th>
                        <th width=10%>Cantidad de RQ cerrado el  d&#237a de hoy {{ $hoy }}</th>
                        <th width=10%>Cantidad de RQ activos al {{$hoy}}</th>
                        <th width=10%>Color seg&#250n cant de RQ vencidos</th>
                    </tr>
                </thead>
                <tbody>
                    @for ($i=0; $i<count((array)$valores["resolutores_array"]); $i++)
                            <tr>
                                <td>{{$valores['equipos_array'][$i]}}</td>
                                <td>{{$valores['resolutores_array'][$i]}}</td>
                                <td style="text-align: center;">{{$valores['pendientes_resolutor_hoy'][$i]+$valores['cerrados'][$i]-$valores['creadoHoy_resolutor'][$i]}}</td>
                                @if ($valores['vencidos'][$i] >= 1)
                                <td style="text-align: center; color: red; font-weight: bold;">{{$valores['vencidos'][$i]}}</td>
                                @else
                                <td style="text-align: center;">{{$valores['vencidos'][$i]}}</td>
                                @endif
                                <td style="text-align: center;">{{$valores['creadoHoy_resolutor'][$i]}}</td>
                                <td style="text-align: center;">{{$valores['cerrados'][$i]}}</td>
                                <td style="text-align: center;">{{$valores['pendientes_resolutor_hoy'][$i]}}</td>
                                @if ($valores['vencidos'][$i] >= 1 and $valores['vencidos'][$i]<=3)
                                <td style="background-color: yellow"></td>
                                @elseif($valores['vencidos'][$i]>=4 and $valores['vencidos'][$i]<=6)
                                <td style="background-color: red"></td>
                                @elseif($valores['vencidos'][$i]>=7)
                                <td style="background-color: #45046a; font-weight: bold; color: white;">GRAVE</td>
                                @else
                                <td style="background-color: green"></td>
                                @endif                               
                            </tr>
                    @endfor
                    <tr>
                        <td colspan="2" style="font-weight: bold;">Total RQ</td>
                        <td style="font-weight: bold;">{{$valores['total_activos_ayer']}}</td>
                        <td style="color: red; font-weight: bold;">{{$valores['total_vencidos']}}</td>
                        <td style="font-weight: bold;">{{$valores['total_creados_hoy']}}</td>
                        <td style="font-weight: bold;">{{$valores['total_cerrados_hoy']}}</td>
                        <td style="font-weight: bold;">{{$valores['total_activos_hoy']}}</td>
                    </tr>
                </tbody>
            </table>
        </table>    
    </div>
</div>

