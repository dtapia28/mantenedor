<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200&display=swap" rel="stylesheet">
<div class="ibox" style="background-color: #E7EDF5;">
    <div style=" opacity:0.3; padding: 10px; text-align: center;">
        <a href="https://app.kinchika.com/login"><img src="{{ asset('img/logo-blue.png') }}" alt="logo"></a>
    </div>
    <div class="ibox-head">
        <div class="ibox-title" align="center"><h1>Reporte Requerimientos</h1></div>       
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
                text-align: left;
            }
            
            tbody th{
                border: 1px solid black;
                text-align: left;
            }
            table,td {
                border: 1px solid black;
            }
            .aj-auto {
                width: auto;
            }
            .bg-verde {
                background-color: #28a745;
            }
            .bg-rojo {
                background-color: #ff3333;
            }
            .bg-morado {
                background-color: #7733ff;
            }
            .bg-amarillo {
                background-color: #fcdf00;
            }
        </style>
        <table align="center" cellpadding="5" cellspacing="5" style="font-family: Arial, Helvetica, sans-serif" border="0">
            <tr >
                <td class="head_table"><h2>Requerimientos al {{ $hoy }}</h2></td>
            </tr>
            <table border="0" style="border: 1px solid #343a40;" cellpadding="5" cellspacing="0">
                <thead>
                    <tr style="color: #f8f9fa; background-color: #343a40; border: 1px solid #f8f9fa;">
                        <th class="aj-auto">Área</th>
                        <th class="aj-auto">Resolutor</th>
                        <th class="aj-auto">Cant. de RQ activos<br>al {{$ayer}}</th>
                        <th class="aj-auto">Vencidos</th>
                        <th class="aj-auto">Cant. de RQ generados<br>el día de hoy {{ $hoy }}</th>
                        <th class="aj-auto">Cant. de RQ cerrados<br>el día de hoy {{ $hoy }}</th>
                        <th class="aj-auto">Cant. de RQ activos <br>al {{$hoy}}</th>
                        <th class="aj-auto">Color según cant.<br>de RQ vencidos</th>
                    </tr>
                </thead>
                <tbody>
                    @for ($i=0; $i<count((array)$valores["resolutores_array"]); $i++)
                            <tr>
                                <td style="border: 1px solid #343a40;">{{$valores['equipos_array'][$i]}}</td>
                                <td style="border: 1px solid #343a40;">{{$valores['resolutores_array'][$i]}}</td>
                                <td style="text-align: center; border: 1px solid #343a40;">{{$valores['pendientes_resolutor_hoy'][$i]+$valores['cerrados'][$i]-$valores['creadoHoy_resolutor'][$i]}}</td>
                                @if ($valores['vencidos'][$i] >= 1)
                                <td style="text-align: center; color: red; font-weight: bold; border: 1px solid #343a40;">{{$valores['vencidos'][$i]}}</td>
                                @else
                                <td style="text-align: center; border: 1px solid #343a40;">{{$valores['vencidos'][$i]}}</td>
                                @endif
                                <td style="text-align: center; border: 1px solid #343a40;">{{$valores['creadoHoy_resolutor'][$i]}}</td>
                                <td style="text-align: center; border: 1px solid #343a40;">{{$valores['cerrados'][$i]}}</td>
                                <td style="text-align: center; border: 1px solid #343a40;">{{$valores['pendientes_resolutor_hoy'][$i]}}</td>
                                @if ($valores['vencidos'][$i] >= 1 and $valores['vencidos'][$i]<=3)
                                <td class="bg-amarillo" style="border: 1px solid #343a40;"></td>
                                @elseif($valores['vencidos'][$i]>=4 and $valores['vencidos'][$i]<=6)
                                <td class="bg-rojo" style="background-color: #dc3545; border: 1px solid #343a40;"></td>
                                @elseif($valores['vencidos'][$i]>=7)
                                <td class="bg-morado" style="font-weight: bold; color: white; text-align: center; border: 1px solid #343a40;">GRAVE</td>
                                @else
                                <td class="bg-verde" style="border: 1px solid #343a40;"></td>
                                @endif                               
                            </tr>
                    @endfor
                    <tr>
                        <td colspan="2" style="font-weight: bold; border: 1px solid #343a40;">Total RQ</td>
                        <td style="font-weight: bold; text-align: center; border: 1px solid #343a40;">{{$valores['total_activos_ayer']}}</td>
                        <td style="color: red; font-weight: bold; text-align: center; border: 1px solid #343a40;">{{$valores['total_vencidos']}}</td>
                        <td style="font-weight: bold; text-align: center; border: 1px solid #343a40;">{{$valores['total_creados_hoy']}}</td>
                        <td style="font-weight: bold; text-align: center; border: 1px solid #343a40;">{{$valores['total_cerrados_hoy']}}</td>
                        <td style="font-weight: bold; text-align: center; border: 1px solid #343a40;">{{$valores['total_activos_hoy']}}</td>
                    </tr>
                </tbody>
            </table>
        </table>
        <br>
        <table border="0" width="100%" style="border: 1px solid #343a40;" cellpadding="5" cellspacing="0">
            <thead>
                <tr style="color: #f8f9fa; background-color: #343a40; border: 1px solid #343a40;">
                    <th class="aj-auto">Semáforo</th>
                    @foreach($valores["dias"] as $dia)
                    <th class="aj-auto">{{$dia}}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
            @for ($i=0; $i < count((array)$valores["resolutores_array"]); $i++)
            @foreach($valores['dias'] as $dia)
            @if($valores['dias'][0] == $dia)
            <tr>
                <td class="aj-auto" style="border: 1px solid #343a40; white-space: nowrap;">{{$valores['resolutores_array'][$i]}}</td>
            @endif
                @foreach($valores['todo'] as $elemento)
                    @foreach($valores['array_id_resolutor'] as $res)
                        @if($elemento->fecha == $dia and $valores['resolutores_array'][$i] == $res[0]->nombreResolutor and $res[0]->id == $elemento->id_resolutor)
                            @if ($elemento->nota >= 1 and $elemento->nota <=3)
                                <td class="bg-amarillo" style="border: 1px solid #343a40;"></td>
                            @elseif($elemento->nota >=4 and $elemento->nota <=6)
                                <td class="bg-rojo" style="border: 1px solid #343a40;"></td> 
                            @elseif($elemento->nota >=7)
                                <td class="bg-morado" style="font-weight: bold; color: white; text-align: center; border: 1px solid #343a40;">GRAVE</td>
                            @else
                                <td class="bg-verde" style="border: 1px solid #343a40;"></td>
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
        <table border="0" width="100%" style="border: 1px solid #343a40;" cellpadding="5" cellspacing="0">
            <thead>
                <tr style="color: #f8f9fa; background-color: #343a40; border: 1px solid #f8f9fa;">
                    <th class="aj-auto">Nota</th>
                    @foreach($valores["dias"] as $dia)
                    <th class="aj-auto">{{$dia}}</th>
                    @endforeach
                    <th class="aj-auto" style="text-align: center">Promedio</th>
                </tr>
            </thead>
            <tbody>
            @for ($i=0; $i < count((array)$valores["resolutores_array"]); $i++)
            @foreach($valores['dias'] as $dia)
            @if($valores['dias'][0] == $dia)
            <tr>
                    <td class="aj-auto" style="border: 1px solid #343a40; white-space: nowrap">{{$valores['resolutores_array'][$i]}}</td>
            @endif
                    @foreach($valores['todo'] as $elemento)
                    @foreach($valores['array_id_resolutor'] as $res)
                    @if($elemento->fecha == $dia and $valores['resolutores_array'][$i] == $res[0]->nombreResolutor and $res[0]->id == $elemento->id_resolutor)
                                @if ($elemento->nota >= 1 and $elemento->nota <=3)
                                <td style="text-align: center; border: 1px solid #343a40;">6</td>
                                @elseif($elemento->nota >=4 and $elemento->nota <=6)
                                <td style="text-align: center; border: 1px solid #343a40;">4</td>
                                @elseif($elemento->nota >=7)
                                <td style="text-align: center; border: 1px solid #343a40;">1</td>
                                @else
                                <td style="text-align: center; border: 1px solid #343a40;">10</td>
                                @endif
                    @endif
                    @endforeach
                    @endforeach
            @endforeach
            <td style="text-align: center; font-weight: bold; border: 1px solid #343a40;">{{$valores['totales_notas'][$i]}}</td>
            </tr>
            @endfor
            </tbody>            
        </table>
    </div>
</div>

