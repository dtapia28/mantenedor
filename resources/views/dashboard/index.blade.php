@extends('Bases.dashboard')
@section('titulo', 'Tablero')
@section('script')
  <!-- Google Charts -->

@endsection
@section('contenido')
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
          title: 'Requerimientos de todos los equipos',
          pieHole: 0.3,
          colors: ['#35A41D', '#CBA20B', '#BB3125'],
        };

        var chart = new google.visualization.PieChart(document.getElementById('donutchart3'));
       function selectHandler() {
          var selectedItem = chart.getSelection()[0];
          if (selectedItem) {
            var topping = data.getValue(selectedItem.row, 0);
            alert('The user selected ' + topping);
          }
        }

        google.visualization.events.addListener(chart, 'select', selectHandler);        
        chart.draw(data, options);
      }
    </script>           

    <!--Div that will hold the pie chart-->
    <div id="graficos">
    <div id="donutchart3" style="width: 900px; height: 500px;"></div>      
    </div>


@endsection