@extends('Bases.dashboard')
@section('titulo', 'Tablero')
@section('script')
  <!-- Google Charts -->

@endsection
@section('contenido')
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
        <div class="row">
          <div class="col-xl-4 col-sm-6 mb-3">
            <div class="card text-white bg-success o-hidden h-100">
              <div class="card-body">
                <div class="card-body-icon">

                </div>
                <div class="mr-5">{{ $verde}} requerimiento(s)</div>
              </div>
              <a class="card-footer text-white clearfix small z-1" href="dashboard/green">
                <span class="float-left">Ver Detalle</span>
                <span class="float-right">
                  <i class="fas fa-angle-right"></i>
                </span>
              </a>
            </div>
          </div>          
          <div class="col-xl-4 col-sm-6 mb-3">
            <div class="card text-white bg-warning o-hidden h-100">
              <div class="card-body">
                <div class="card-body-icon">

                </div>
                <div class="mr-5">{{ $amarillo }} requerimiento(s)</div>
              </div>
              <a class="card-footer text-white clearfix small z-1" href="dashboard/yellow">
                <span class="float-left">Ver Detalle</span>
                <span class="float-right">
                  <i class="fas fa-angle-right"></i>
                </span>
              </a>
            </div>
          </div>

          <div class="col-xl-4 col-sm-6 mb-3">
            <div class="card text-white bg-danger o-hidden h-100">
              <div class="card-body">
                <div class="card-body-icon">

                </div>
                <div class="mr-5">{{ $rojo }} requerimiento(s)</div>
              </div>
              <a class="card-footer text-white clearfix small z-1" href="dashboard/red">
                <span class="float-left">Ver Detalle</span>
                <span class="float-right">
                  <i class="fas fa-angle-right"></i>
                </span>
              </a>
            </div>
          </div>
        </div>

<!-- Prueba de graficos con Chart.js-->
<link rel="stylesheet" type="text/css" href="{{ asset('css/index_style.css') }}">
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
google.charts.load('current', {'packages':['corechart']});
<?php
foreach ($equipos2 as $valor) {
  echo "google.charts.setOnLoadCallback(drawChart".$valor['id'].");\n";
}
?>
<?php
foreach ($equipos2 as $valor) {
  echo "function drawChart".$valor['id']."(){
    var data = new google.visualization.arrayToDataTable([
    ['Segmento','Cantidad'],
    ['Al día',".$valor['verde']."],
    ['Por vencer',".$valor['amarillo']."],
    ['Vencido',".$valor['rojo']."]
    ]);

    var options = {
      title: 'Equipo ".$valor['nombre']."',
      pieHole:0.2,
      colors: ['#35A41D', '#CBA20B', '#BB3125'],
    };

    var chart".$valor['id']." = new google.visualization.PieChart(document.getElementById('".$valor['id']."_chart_div'));
    chart".$valor['id'].".draw(data, options);
  }\n";
}
?>
</script>


    <!--Div that will hold the pie chart-->
    <?php
    foreach ($equipos2 as $valor) {
      echo "<div id='".$valor['id']."_chart_div' style='width: 500px; height: 300px;'></div>\n";
    }  
    ?>
    <div id="graficos">
    <div id="test_dataview" style="width: 900px; height: 500px;"></div>      


@endsection