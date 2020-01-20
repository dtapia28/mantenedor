@extends('Bases.dashboard')
@section('titulo', 'Tablero')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/css/tempusdominus-bootstrap-4.min.css" />
    <style>
    .chart {
        width: 100%; 
        min-height: 445px;
    }
    .chart-bar {
        width: 90%; 
        min-height: 445px;
    }
    </style>
@endsection

@section('contenido')
<div class="page-content fade-in-up">
    <div class="ibox">
        <div class="ibox-body row">
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-4">
                        <small class="text-dark"><i class="fa fa-calendar-check-o"></i> Período</small>
                        <select name="rango_fecha" id="rango_fecha" class="form-control" onchange="validarTipo(this.value)">
                            <option value="mes_actual">Mes actual</option>
                            <option value="mes_ult3">Últimos 3 meses</option>
                            <option value="mes_ult6">Últimos 6 meses</option>
                            <option value="mes_ult12">Últimos 12 meses</option>
                            <option value="por_rango">Por rango</option>
                        </select>
                    </div>
                    <div class="col-md-8">
                        <small class="text-dark"><i class="fa fa-calendar"></i> Rango de fecha</small>
                        <div class="input-daterange input-group" id="datepicker">
                            <span class="input-group-addon p-l-10 p-r-10"><small class="text-dark">desde</small></span>
                            <input type="text" class="form-control datetimepicker-input" id="fec_des" name="fec_des" value="" data-toggle="datetimepicker" data-target="#fec_des" maxlength="16" disabled/>
                            <span class="input-group-addon p-l-10 p-r-10"><small class="text-dark">hasta</small></span>
                            <input type="text" class="form-control datetimepicker-input" id="fec_has" name="fec_has" value="" data-toggle="datetimepicker" data-target="#fec_has" maxlength="16" disabled/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="row">
                    <div class="col-md-6">
                        <small>&nbsp;</small>
                        <a class="btn btn-success btn-block text-white" href="#"><i class="fa fa-filter"></i> Filtrar</a>
                    </div>
                    <div class="col-md-6">
                        <small>&nbsp;</small>
                        <a class="btn btn-warning btn-block text-white" href="{{ url ('dashboard')}}"><i class="fa fa-repeat"></i> Reiniciar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3 col-md-6">
            <div class="ibox bg-success color-white widget-stat">
                <div class="ibox-body">
                    <h2 class="m-b-5 font-strong">201</h2>
                    <div class="m-b-5">REQUERIMIENTOS</div><i class="fa fa-address-card widget-stat-icon"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="ibox bg-info color-white widget-stat">
                <div class="ibox-body">
                    <h2 class="m-b-5 font-strong">32</h2>
                    <div class="m-b-5">RESOLUTORES</div><i class="fa fa-address-book widget-stat-icon"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="ibox bg-warning color-white widget-stat">
                <div class="ibox-body">
                    <h2 class="m-b-5 font-strong">50</h2>
                    <div class="m-b-5">SOLICITANTES</div><i class="fa fa-address-book-o widget-stat-icon"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="ibox bg-danger color-white widget-stat">
                <div class="ibox-body">
                    <h2 class="m-b-5 font-strong">24</h2>
                    <div class="m-b-5">EQUIPOS</div><i class="fa fa-users widget-stat-icon"></i>
                </div>
            </div>
        </div>
    </div>
    @if ($user[0]->nombre == "administrador" || $user[0]->nombre == "supervisor")
        @include('dashboard.administrador')
    @endif
    @if($user[0]->nombre == 'solicitante')
        @include('dashboard.solicitante')
    @endif
    @if($user[0]->nombre == 'resolutor')
        @include('dashboard.resolutor')
    @endif
    @if($user[0]->nombre == 'resolutor_lider')
        @include('dashboard.resolutor_lider')
    @endif
@endsection

@section('script')
<<<<<<< HEAD
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
  google.charts.load("current", {packages:["corechart"]});
  google.charts.setOnLoadCallback(drawChart);
  function drawChart() {
    var data = google.visualization.arrayToDataTable([
      ['Task', 'Hours per Day'],
      ['Al día',     {{ $verde }}],
      ['Por vencer',      {{ $amarillo }}],
      ['Vencido',  {{ $rojo }}]
      ]);
    var options = {
      
      title: 'Todos los equipos',
      pieHole: 0.3,
      colors: ['#35A41D', '#CBA20B', '#BB3125'],
      width: '100%',
      height: '445px',
    };
    var chart = new google.visualization.PieChart(document.getElementById('donutchart3'));
    chart.draw(data, options);
  }
</script> 
<script type="text/javascript">
  google.charts.load('current', {'packages':['corechart']});
  <?php
  foreach ($equipos2 as $valor) {
    echo "google.charts.setOnLoadCallback(drawChart".$valor['id'].");\n";
  }
  ?>
  <?php
  foreach ($equipos2 as $valor) {
    echo "function drawChart".$valor['id']."() {
      var data = new google.visualization.arrayToDataTable([
      ['Segmento','Cantidad'],
      ['Al día',".$valor['verde']."],
      ['Por vencer',".$valor['amarillo']."],
      ['Vencido',".$valor['rojo']."]
      ]);

      var options = {
        title: 'Equipo ".$valor['nombre']."',
        pieHole: 0.3,
        width: '100%',
        height: '445px',
        colors: ['#35A41D', '#CBA20B', '#BB3125'],
      };
=======
    <script src="{{ asset('js/moment.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/moment-with-locales.js') }}" type="text/javascript"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/js/tempusdominus-bootstrap-4.min.js"></script>
    <script type="text/javascript">
        menu_activo('mTablero');
>>>>>>> frontend

        function validarTipo(valor) {
            if (valor=='por_rango') {
                $('#fec_des').prop('disabled', false);
                $('#fec_has').prop('disabled', false);
            } else {
                $('#fec_des').val("");
                $('#fec_has').val("");
                $('#fec_des').prop('disabled', true);
                $('#fec_has').prop('disabled', true);
            }
        }

        $(function () {
            $('#fec_des').datetimepicker({
                locale: 'es',
                useCurrent: false,
                format: 'DD/MM/YYYY',
                ignoreReadonly: true,
                forceParse: false,
                maxDate: 'now',
            });
            
            $('#fec_has').datetimepicker({
                locale: 'es',
                useCurrent: false,
                format: 'DD/MM/YYYY',
                ignoreReadonly: true,
                forceParse: false,
                maxDate: 'now',
            });
            
            // Valida que la fecha hasta no sea menor que fecha desde
            $("#fec_des").on("change.datetimepicker", function (e) {
                $('#fec_has').datetimepicker('minDate', e.date);
            });
            $("#fec_has").on("change.datetimepicker", function (e) {
                $('#fec_des').datetimepicker('maxDate', e.date);
            });
        });
    </script>
@endsection
