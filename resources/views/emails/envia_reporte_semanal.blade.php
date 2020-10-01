<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200&display=swap" rel="stylesheet">
<div class="ibox" style="background-color: #E7EDF5;">
    <div style=" opacity:0.3; padding: 10px; text-align: center;">
        <a href="https://app.kinchika.com/login"><img src="{{ asset('img/logo-blue.png') }}" alt="logo"></a>
    </div>
    <div class="ibox-head">
        <div class="ibox-title"><h1>Reporte Requerimientos</h1></div>       
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
            table,td {
                border: 1px solid black;
            }           
        </style>
        <table width=80%>
            <tr >
                <td class="head_table"><h2>Requerimientos al {{ $hoy }}</h2></td>
            </tr>
            <table border="2" width=80%>
                <thead>
                    <tr>
                        <th width=10%>Area</th>
                        <th width=20%>Resolutor</th>
                        <th width=10%>Cantidad de RQ activos al {{$ayer}}</th>
                        <th width=10%>Vencidos</th>
                        <th width=10%>Cantidad de RQ generado el dia de hoy {{ $hoy }}</th>
                        <th width=10%>Cantidad de RQ cerrado el  dia de hoy {{ $hoy }}</th>
                        <th width=10%>Cantidad de RQ activos al {{$hoy}}</th>
                        <th width=10%>Color segun cant de RQ vencidos</th>
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
                                <td style="background-color: #45046a; font-weight: bold; color: white; text-align: center;">GRAVE</td>
                                @else
                                <td style="background-color: green"></td>
                                @endif                               
                            </tr>
                    @endfor
                    <tr>
                        <td colspan="2" style="font-weight: bold;">Total RQ</td>
                        <td style="font-weight: bold; text-align: center;">{{$valores['total_activos_ayer']}}</td>
                        <td style="color: red; font-weight: bold; text-align: center;">{{$valores['total_vencidos']}}</td>
                        <td style="font-weight: bold; text-align: center;">{{$valores['total_creados_hoy']}}</td>
                        <td style="font-weight: bold; text-align: center;">{{$valores['total_cerrados_hoy']}}</td>
                        <td style="font-weight: bold; text-align: center;">{{$valores['total_activos_hoy']}}</td>
                    </tr>
                </tbody>
            </table>
        </table>
        <br>
        <table border='1'>
            <thead>
                <tr>
                    <th>Semaforo</th>
                    @foreach($valores["dias"] as $dia)
                    <th>{{$dia}}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
            @for ($i=0; $i < count((array)$valores["resolutores_array"]); $i++)
            @foreach($valores['dias'] as $dia)
            @if($valores['dias'][0] == $dia)
            <tr>
                    <td>{{$valores['resolutores_array'][$i]}}</td>
            @endif
                    @foreach($valores['todo'] as $elemento)
                    @foreach($valores['array_id_resolutor'] as $res)
                    @if($elemento->fecha == $dia and $valores['resolutores_array'][$i] == $res[0]->nombreResolutor and $res[0]->id == $elemento->id_resolutor)
                                @if ($elemento->nota >= 1 and $elemento->nota <=3)
                                <td style="background-color: yellow"></td>
                                @elseif($elemento->nota >=4 and $elemento->nota <=6)
                                <td style="background-color: red"></td>
                                @elseif($elemento->nota >=7)
                                <td style="background-color: #45046a; font-weight: bold; color: white; text-align: center;">GRAVE</td>
                                @else
                                <td style="background-color: green"></td>
                                @endif
                    @endif
                    @endforeach
                    @endforeach
            @endforeach
            </tr>
            @endfor
            </tbody>
        </table>
        <br>
        <table border='1'>
            <thead>
                <tr>
                    <th>Nota</th>
                    @foreach($valores["dias"] as $dia)
                    <th>{{$dia}}</th>
                    @endforeach
                    <th style="text-align: center">Promedio</th>
                </tr>
            </thead>
            <tbody>
            @for ($i=0; $i < count((array)$valores["resolutores_array"]); $i++)
            @foreach($valores['dias'] as $dia)
            @if($valores['dias'][0] == $dia)
            <tr>
                    <td>{{$valores['resolutores_array'][$i]}}</td>
            @endif
                    @foreach($valores['todo'] as $elemento)
                    @foreach($valores['array_id_resolutor'] as $res)
                    @if($elemento->fecha == $dia and $valores['resolutores_array'][$i] == $res[0]->nombreResolutor and $res[0]->id == $elemento->id_resolutor)
                                @if ($elemento->nota >= 1 and $elemento->nota <=3)
                                <td style="text-align: center">6</td>
                                @elseif($elemento->nota >=4 and $elemento->nota <=6)
                                <td style="text-align: center">4</td>
                                @elseif($elemento->nota >=7)
                                <td style="text-align: center">1</td>
                                @else
                                <td style="text-align: center">10</td>
                                @endif
                    @endif
                    @endforeach
                    @endforeach
            @endforeach
            <td style="text-align: center; font-weight: bold;">{{$valores['totales_notas'][$i]}}</td>
            </tr>
            @endfor
            </tbody>            
        </table>
    </div>
</div>

