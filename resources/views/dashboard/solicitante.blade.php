<div class="ibox">
    <div class="ibox-body" align="center">
		<div id="chart-dona" style="min-height:50vh"></div>
    </div>
</div>

<div class="ibox">
  <div class="ibox-body" align="center">
    <div id="chart-apilado" style="min-height:60vh"></div>
  </div>
</div>

@section('scripts_dash')
	<script type="text/javascript">
		$("#chart-dona").insertFusionCharts({
			type: "doughnut3d",
			width: "100%",
			height: "100%",
			dataFormat: "json",
			dataSource: {
				chart: {
					caption: "Requerimientos por Estatus",
					enablesmartlabels: "1",
					showlabels: "1",
					numbersuffix: " MMbbl",
					exportEnabled: "1",
					usedataplotcolorforlabels: "1",
					plottooltext: "$label: <b>$value</b>",
					theme: "fusion"
				},	
				data: [
				{
					label: "Al Día",
					value: "290",
					color: "#2ecc71",
				},
				{
					label: "Por Vencer",
					value: "260",
					color: "#f39c12",
				},
				{
					label: "Vencidos",
					value: "180",
					color: "#e74c3c",
				}
				]
			}
		});

		const equipos = ["Matzikama", "Cederberg", "Bergrivier", "Swartland", "Witzenberg", "Langeberg", "George", "Hessequa", "Laingsburg"];
		const vencidos = ["357", "267", "555", "679", "138", "852", "357", "664", "855"];
		const alDia = ["356", "173", "635", "144", "588", "870", "883", "306", "679"];
		const porVencer = ["635", "263", "346", "356", "619", "588", "174", "883", "870"];
		
		var nombreEquipos = [];
		equipos.forEach(element => {
			var obj = {};
			obj.label = element;
			nombreEquipos.push(obj);
			return nombreEquipos;
		});

		var valoresVencidos = [];
		vencidos.forEach(element => {
			var obj = {};
			obj.value = element;
			valoresVencidos.push(obj);
			return valoresVencidos;
		});

		var valoresAlDia = [];
		alDia.forEach(element => {
			var obj = {};
			obj.value = element;
			valoresAlDia.push(obj);
			return valoresAlDia;
		});

		var valoresPorVencer = [];
		porVencer.forEach(element => {
			var obj = {};
			obj.value = element;
			valoresPorVencer.push(obj);
			return valoresPorVencer;
		});
		
		$("#chart-apilado").insertFusionCharts({
			type: "stackedcolumn3d",
			width: "100%",
			height: "100%",
			dataFormat: "json",
			dataSource: {
				chart: {
					caption: "Requerimientos por Equipo",
					subcaption: "Por Estatus",
					numvisibleplot: "6",
					showvalues: "1",
					decimals: "0",
					// stack100percent: "1",
					valuefontcolor: "#FFFFFF",
					exportEnabled: "1",
					plottooltext:
						"$label tiene $dataValue $seriesName",
					theme: "fusion"
				},
				categories: [
				{
					category: nombreEquipos
				}
				],
				dataset: [
				{
					seriesname: "Vencidos",
					data: valoresVencidos,
					color: "#e74c3c",
				},
				{
					seriesname: "Por Vencer",
					data: valoresPorVencer,
					color: "#f39c12",
				},
				{
					seriesname: "Al día",
					data: valoresAlDia,
					color: "#2ecc71",
				}
				]
			}
		});
    </script>
@endsection
