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
    <div id="panel">
        <div id="panel-admin">
            <div class="panel-admin-box">
                <div id="tootlbar_colors">
                    <button class="color" style="background-color:#1abac8;" onclick="changeDisplay1()">1</button>
                    <button class="color" style="background-color:#ff8a00;" onclick="changeDisplay2()"> </button>
                    <button class="color" style="background-color:#b4de50;" onclick="mytheme(2)"> </button>
                    <button class="color" style="background-color:#e54e53;" onclick="mytheme(3)"> </button>
                    <button class="color" style="background-color:#1abc9c;" onclick="mytheme(4)"> </button>
                    <button class="color" style="background-color:#159eee;" onclick="mytheme(5)"> </button>
                </div>
            </div>

        </div>
        <a class="open" href="#"><span><i class="fa fa-gear fa-spin"></i></span></a>
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
          title: 'Todos los teams',
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
    <div id="donutchart3" style="width: 900px; height: 500px;"></div>     
    <div id="graficosTeam">
    <?php
    foreach ($equipos2 as $valor) {
      echo "<div id='".$valor['id']."_chart_div' style='width: 300px; height: 300px;'></div>\n";
    }  
    ?>
    </div>
<script>
function changeDisplay1() {
    document.getElementById("donutchart3").style.display = "none";
    document.getElementById("graficosTeam").style.display = "block";    
}

function changeDisplay2() {
    document.getElementById("donutchart3").style.display = "block";
    document.getElementById("graficosTeam").style.display = "none";    
}
</script>      
@endsection
@section('script')

@endsection