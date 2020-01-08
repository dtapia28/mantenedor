@extends('Bases.dashboard')
@section('titulo', 'Tablero')
@section('css')
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
<div class="page-heading">
  <h1 class="page-title"><i class="fa fa fa-th-large"></i> Tablero</h1>
</div>
<div class="page-content fade-in-up" id="g1">
  <div class="ibox">
    <div class="ibox-head">
      <div class="ibox-title">Gráfico</div>
    </div>
    <div class="ibox-body" align="center">
      <div id="donutchart3" class="chart"></div>
    </div>
  </div>
</div>
<div class="page-content fade-in-up" id="g2">
	<div class="ibox">
		<div class="ibox-head">
			<div class="ibox-title">Gráfico</div>
		</div>
		<div class="ibox-body" align="center">
			<div id="graficosTeam" class="chart" style="display: none;"> 
				<?php
				foreach ($equipos2 as $valor) {
					echo "<div id='".$valor['id']."_chart_div' class='chart'></div>\n";
				}  
				?>
			</div>
		</div>
	</div>
</div>
<div class="page-content fade-in-up" id="g3">
	<div class="ibox">
		<div class="ibox-head">
			<div class="ibox-title">Gráfico</div>
		</div>
		<div class="ibox-body" align="center">
			<div id="reqEquipos" class="chart"></div>
		</div>
	</div>
</div>
<div class="page-content fade-in-up" id="g4">
	<div class="ibox">
		<div class="ibox-head">
			<div class="ibox-title">Gráfico</div>
		</div>
		<div class="ibox-body" align="center">
			<div id="barra" class="chart-bar"></div>
		</div>
	</div>
</div>
<div class="page-content fade-in-up" id="g5">
	<div class="ibox">
		<div class="ibox-head">
			<div class="ibox-title">Gráfico</div>
		</div>
		<div class="ibox-body" align="center">
			<div id="barra2" class="chart-bar"></div>
		</div>
	</div>
</div>

<div class="theme-config">
  <div class="theme-config-toggle"><i class="fa fa-cog theme-config-show"></i><i class="ti-close theme-config-close"></i></div>
  <div class="theme-config-box">
      <div class="text-center font-18 m-b-20">GRÁFICOS</div>
      <div class="font-strong">OPCIONES</div>
      <div class="btn-group-vertical m-r-10">
          <button class="btn btn-primary" onclick="changeDisplay1()"><i class="fa fa-pie-chart"></i> 1</button>
            <button class="btn btn-success" onclick="changeDisplay2()"><i class="fa fa-pie-chart"></i> 2</button>
            <button class="btn btn-warning" onclick="changeDisplay3()"><i class="fa fa-pie-chart"></i> 3</button>
      </div>
      <div class="btn-group-vertical">
          <button class="btn btn-primary" onclick="changeDisplay4()"><i class="fa fa-bar-chart"></i> 4</button>
          <button class="btn btn-success" onclick="changeDisplay5()"><i class="fa fa-bar-chart"></i> 5</button>
          <button class="btn btn-warning" onclick="mytheme(5)"><i class="fa fa-bar-chart"></i> 6</button>
      </div>
  </div>
</div>
@endsection

@section('script')
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

      var chart".$valor['id']." = new google.visualization.PieChart(document.getElementById('".$valor['id']."_chart_div'));
      chart".$valor['id'].".draw(data, options);
    }\n";
  }
  ?>
</script>
<script type="text/javascript">
  google.charts.load('current', {'packages':['corechart']});
  google.charts.setOnLoadCallback(equipos);
  function equipos(){
    var data = new google.visualization.arrayToDataTable([
      ['Segmento','Cantidad'],
      <?php
      foreach ($equipos2 as $equipo) {
        echo "['".$equipo['nombre']."',".$equipo['conteo']."],";
      }

      ?>
      ]);
    var options = {
      width: '100%',
      height: '445px',
      title: 'Requerimientos por equipo',
      pieHole: 0.3
    };
    var equi = new google.visualization.PieChart(document.getElementById('reqEquipos'));
    equi.draw(data, options);     
  }
</script>
<script type="text/javascript">
  google.charts.load('current', {'packages':['bar']});
  google.charts.setOnLoadCallback(equiposBarra);
  function equiposBarra(){
    var data = new google.visualization.arrayToDataTable([
      ['Segmento','Cantidad'],
      <?php
      foreach ($equipos2 as $equipo) {
        echo "['".$equipo['nombre']."',".$equipo['conteo']."],\n";
      }

      ?>
      ]);
    var options = {
      title: 'Requerimientos por equipo',
      width: '90%',
      height: '445px',
      legend: {position: 'none'},
      chart: {title: 'Cantidad de requerimientos por equipo'},
      bars: 'vertical',        
      axes: {
        x: {
          0: {side:'bottom', label: 'Equipos'}
        }
      },
      bar: {groupWidth: "90%"}
    };
    var bar = new google.charts.Bar(document.getElementById('barra'));
    bar.draw(data, options);     
  }
</script>
<script type="text/javascript">
  google.charts.load('current', {'packages':['bar']});
  google.charts.setOnLoadCallback(seccionBarra);
  function seccionBarra(){
    var data = new google.visualization.arrayToDataTable([
      ['Equipos', 'Al día', {role: 'style'}, 'Por vencer', {role: 'style'}, 'Vencido', {role: 'style'}],
      <?php
        foreach ($equipos2 as $equipo) {
          echo "['".$equipo['nombre']."', ".$equipo['verde'].", 'color: green', ".$equipo['amarillo'].", 'color: yellow', ".$equipo['rojo'].", 'color: red'],\n ";
        }
      ?>
      ]);

    var options = {
      width: '90%',
      height: '445px',
      colors: ['#35A41D', '#CBA20B', '#BB3125'],
      chart: {
        title: 'Requerimientos por equipo'
      },
      // series: {
      //   0: {axis: 'Al día'},
      //   1: {axis: 'Por vencer'},
      //   2: {axis: 'Vencido'}
      // }
    };
    var bar2 = new google.charts.Bar(document.getElementById('barra2'));
    bar2.draw(data, options);      
  }
</script>
<script type="text/javascript">
  menu_activo('mTablero');
  function changeDisplay1() {
    document.getElementById("donutchart3").style.display = "block";
    document.getElementById("graficosTeam").style.display = "none";
    document.getElementById('reqEquipos').style.display = "none";
    document.getElementById('barra').style.display = "none";  
    document.getElementById('barra2').style.display = "none";
	$("#g1").show(); $("#g2").hide(); $("#g3").hide(); $("#g4").hide(); $("#g5").hide();
  }

  function changeDisplay2() {
    document.getElementById("donutchart3").style.display = "none";
    document.getElementById("graficosTeam").style.display = "block";
    document.getElementById('reqEquipos').style.display = "none";
    document.getElementById('barra').style.display = "none";
    document.getElementById('barra2').style.display = "none";
	$("#g1").hide(); $("#g2").show(); $("#g3").hide(); $("#g4").hide(); $("#g5").hide();
  }
  function changeDisplay3() {
    document.getElementById("donutchart3").style.display = "none";
    document.getElementById("graficosTeam").style.display = "none";
    document.getElementById('reqEquipos').style.display = "block";
    document.getElementById('barra').style.display = "none"; 
    document.getElementById('barra2').style.display = "none";
	$("#g1").hide(); $("#g2").hide(); $("#g3").show(); $("#g4").hide(); $("#g5").hide();
  }

  function changeDisplay4() {
    document.getElementById("donutchart3").style.display = "none";
    document.getElementById("graficosTeam").style.display = "none";
    document.getElementById('reqEquipos').style.display = "none";
    document.getElementById('barra').style.display = "block";
    document.getElementById('barra2').style.display = "none";
	$("#g1").hide(); $("#g2").hide(); $("#g3").hide(); $("#g4").show(); $("#g5").hide();
  }

  function changeDisplay5(){
    document.getElementById("donutchart3").style.display = "none";
    document.getElementById("graficosTeam").style.display = "none";
    document.getElementById('reqEquipos').style.display = "none";
    document.getElementById('barra').style.display = "none";
    document.getElementById('barra2').style.display = "block";
	$("#g1").hide(); $("#g2").hide(); $("#g3").hide(); $("#g4").hide(); $("#g5").show();
  }
</script>
@endsection
