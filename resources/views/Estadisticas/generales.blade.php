<div class="row">
    <div class="col-lg-4 col-md-12">
        <div class="ibox bg-warning color-white widget-stat">
            <div class="ibox-body">
                <h2 class="m-b-5 font-strong">{{ $indicadores['abiertos'] }}</h2>
                <div class="m-b-5">REQUERIMIENTOS ABIERTOS</div><i class="ti-receipt widget-stat-icon"></i>
                {{-- <div><i class="fa fa-level-up m-r-5"></i><small>25% higher</small></div> --}}
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-12">
        <div class="ibox bg-success color-white widget-stat">
            <div class="ibox-body">
                <h2 class="m-b-5 font-strong">{{ $indicadores['cerrados'] }}</h2>
                <div class="m-b-5">REQUERIMIENTOS CERRADOS</div><i class="ti-check-box widget-stat-icon"></i>
                {{-- <div><i class="fa fa-level-up m-r-5"></i><small>17% higher</small></div> --}}
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-12">
        <div class="ibox bg-primary color-white widget-stat">
            <div class="ibox-body">
                <h2 class="m-b-5 font-strong">{{ $indicadores['total'] }}</h2>
                <div class="m-b-5">TOTAL REQUERIMIENTOS</div><i class="ti-agenda widget-stat-icon"></i>
                {{-- <div><i class="fa fa-level-up m-r-5"></i><small>22% higher</small></div> --}}
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-5">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title"><h4 class="m-0">(%) de requerimientos por área</h4></div>
                <div class="ibox-tools">
                    <a class="ibox-collapse"><i class="fa fa-minus"></i></a>
                </div>
            </div>
            <div class="ibox-body">
                <div class="mb-4">
                    <div>
                        <div id="chart_porc_req_area" style="height:300px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title"><h4 class="m-0">Cantidad de requerimientos por área</h4></div>
                <div class="ibox-tools">
                    <a class="ibox-collapse"><i class="fa fa-minus"></i></a>
                </div>
            </div>
            <div class="ibox-body">
                <div class="mb-4">
                    <div>
                        <div id="chart_cant_req_area" style="height:300px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title"><h4 class="m-0">Requerimientos por mes</h4></div>
                <div class="ibox-tools">
                    <a class="ibox-collapse"><i class="fa fa-minus"></i></a>
                </div>
            </div>
            <div class="ibox-body">
                <div class="mb-4">
                    <div>
                        <div id="chart_rep_mes" style="height:450px;"></div>    
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title"><h4 class="m-0">Semáforo mensual</h4></div>
                <div class="ibox-tools">
                    <a class="ibox-collapse"><i class="fa fa-minus"></i></a>
                </div>
            </div>
            <div class="ibox-body">
                <div class="mb-4">
                    <div>
                        <div id="chart_semaforo" style="height:450px;"></div>    
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-5">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Requerimientos por área</div>
                <div class="ibox-tools">
                    <a class="ibox-collapse"><i class="fa fa-minus"></i></a>
                </div>
            </div>
            <div class="ibox-body" style="height:400px;">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Área</th>
                            <th>Cantidad Req.</th>
                            <th>Porcentaje</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $total_reqs = 0;
                            foreach ($req_area as $item) {
                                $total_reqs += $item->cant_reqs;
                            }    
                        @endphp
                        @foreach ($req_area as $item)
                        <tr>
                            <td>{{$item->area}}</td>
                            <td>{{$item->cant_reqs}}</td>
                            <td>{{number_format(($item->cant_reqs * 100) / $total_reqs, 2, ',', '.')}}%</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-dark text-white">
                            <th>Total</th>
                            <th>{{$total_reqs}}</th>
                            <th>100%</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Requerimientos por semana</div>
                <div class="ibox-tools">
                    <a class="ibox-collapse"><i class="fa fa-minus"></i></a>
                </div>
            </div>
            <div class="ibox-body" style="height:400px;">
                <table class="table table-striped table-hover visitors-table">
                    <thead>
                        <tr>
                            <th>Nro. Semana</th>
                            <th>Cantidad Req.</th>
                            <th>Req. Abiertos</th>
                            <th>Req. Cerrados</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $total_reqs2 = $total_abie = $total_cerr = 0;
                            foreach ($tabla_sem as $item) {
                                $total_abie += $item['abiertos'];
                                $total_cerr += $item['cerrados'];
                            }
                            $total_reqs2 = $total_abie + $total_cerr;
                        @endphp
                        @foreach ($tabla_sem as $item)
                        <tr>
                            <td>Semana {{$item['semana']}}</td>
                            <td>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-primary" role="progressbar" style="width:100%; height:5px;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="progress-parcent"><strong>{{($item['abiertos'] + $item['cerrados'])}}</strong> (100%)</span>
                            </td>
                            <td>
                                <div class="progress">
                                    @php
                                        if (($item['abiertos'] + $item['cerrados']) > 0)
                                            $por_abi = number_format(($item['abiertos'] * 100 ) / ($item['abiertos'] + $item['cerrados']), 0, ',', '.');
                                        else
                                            $por_abi = 0;
                                    @endphp
                                    <div class="progress-bar progress-bar-warning" role="progressbar" style="width:{{$por_abi}}%; height:5px;" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="progress-parcent"><strong>{{$item['abiertos']}}</strong> ({{number_format($por_abi, 2, ',', '.')}}%)</span>
                            </td>
                            <td>
                                <div class="progress">
                                    @php
                                        if (($item['abiertos'] + $item['cerrados']) > 0)
                                            $por_cer = number_format(($item['cerrados'] * 100 ) / ($item['abiertos'] + $item['cerrados']), 0, ',', '.');
                                        else
                                            $por_cer = 0;
                                    @endphp
                                    <div class="progress-bar progress-bar-success" role="progressbar" style="width:{{$por_cer}}%; height:5px;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="progress-parcent"><strong>{{$item['cerrados']}}</strong> ({{number_format($por_cer, 2, ',', '.')}}%)</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-dark text-white">
                            <th>Total</th>
                            <th>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-primary" role="progressbar" style="width:100%; height:5px;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="progress-parcent">{{$total_reqs2}} (100%)</span>
                            </th>
                            <th>
                                <div class="progress">
                                    @php
                                        if (($total_abie + $total_cerr) > 0)
                                            $por_tot_abi = ($total_abie * 100 ) / ($total_abie + $total_cerr);
                                        else
                                            $por_tot_abi = 0;
                                    @endphp
                                    <div class="progress-bar progress-bar-warning" role="progressbar" style="width:{{$por_tot_abi}}%; height:5px;" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="progress-parcent">{{$total_abie}} ({{number_format($por_tot_abi, 2, ',', '.')}}%)</span>
                            </th>
                            <th>
                                <div class="progress">
                                    @php
                                        if (($total_abie + $total_cerr) > 0)
                                            $por_tot_cer = ($total_cerr * 100 ) / ($total_abie + $total_cerr);
                                        else
                                            $por_tot_cer = 0;
                                    @endphp
                                    <div class="progress-bar progress-bar-success" role="progressbar" style="width:{{$por_tot_cer}}%; height:5px;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="progress-parcent">{{$total_cerr}} ({{number_format($por_tot_cer, 2, ',', '.')}}%)</span>
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

@section('scripts_dash')
    <script type="text/javascript">
        // Gráfico % de requerimientos por área
        $("#chart_porc_req_area").insertFusionCharts({
            type: "doughnut3d",
            width: "100%",
            height: "100%",
            dataFormat: "json",
            dataSource: {
                chart: {
                    enablesmartlabels: "1",
                    showlabels: "1",
                    usedataplotcolorforlabels: "1",
                    plottooltext: "$label, <b>$value</b>",
                    theme: "fusion",
                },
                data: <?=$req_area1?>
            }
        });
        // Gráfico cantidad de requerimientos por área
        $("#chart_cant_req_area").insertFusionCharts({
            type: "column2d",
            width: "100%",
            height: "100%",
            dataFormat: "json",
            dataSource: {
                chart: {
                    xaxisname: "Áreas",
                    yaxisname: "Cantidad",
                    theme: "fusion",
                    showValues: 1,
                    showShadow: 1,
                    paletteColors: "#565aa0, #30b3af, #d96c6a, #eab93d",
                },
                data: <?=$req_area1?>
            }
        });
        // Gráfico requerimientos por mes
        $("#chart_rep_mes").insertFusionCharts({
            type: "mscolumn2d",
            width: "100%",
            height: "100%",
            dataFormat: "json",
            dataSource: {
                chart: {
                    xaxisname: "Áreas",
                    yaxisname: "Cantidad",
                    formatnumberscale: "1",
                    plottooltext:
                        "<b>$dataValue</b> reqs. en <b>$seriesName</b> del área <b>$label</b>",
                    theme: "fusion",
                    drawcrossline: "1",
                    showValues: "1",
                    rotateValues: "1",
                    // placeValuesInside: "1",
                    valueFontSize: "10px"
                },
                categories: [
                {
                    category: <?=$areas?>
                }
                ],
                dataset: <?=$array_data?>
            }
        });
        // Gráfico semáforo mensual
        $("#chart_semaforo").insertFusionCharts({
            type: "mscolumn2d",
            width: "100%",
            height: "100%",
            dataFormat: "json",
            dataSource: {
                chart: {
                    xaxisname: "Áreas",
                    yaxisname: "Cantidad",
                    formatnumberscale: "1",
                    plottooltext:
                        "<b>$dataValue</b> reqs. en <b>$seriesName</b> del área <b>$label</b>",
                    theme: "fusion",
                    drawcrossline: "1"
                },
                categories: [
                {
                    category: <?=$areas?>
                }
                ],
                dataset: <?=$array_data?>
            }
        });
    </script>
@endsection
