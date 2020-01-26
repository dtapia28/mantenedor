<div class="ibox">
	<div class="ibox-body" align="center">
		<div id="chart-apilado" style="min-height:60vh"></div>
	</div>
</div>
<div class="ibox">
    <div class="ibox-body" align="center">
		<div id="chart-medidor-gral" style="min-height:40vh"></div>
    </div>
</div>
<div class="ibox">
	<div class="ibox-head">
		<div class="ibox-title">Requerimientos por Equipo</div>
		<div class="ibox-tools">
			<a class="ibox-collapse"><i class="fa fa-minus"></i></a>
		</div>
	</div>
	<div class="ibox-body" align="center">
		<div class="row">
			@for (	$i=0; $i<8; $i++)
			<div class="col-md-3">
				<div id="chart-medidor-eq{{$i}}" style="min-height:30vh;"></div>
			</div>
			@endfor
		</div>
	</div>
</div>

@section('scripts_dash')
	<script type="text/javascript">
		const equipos = ["Matzikama", "Cederberg", "Bergrivier", "Swartland", "Witzenberg", "Langeberg", "George", "Laingsburg"];
		const vencidos = ["357", "267", "555", "679", "238", "852", "357", "855"];
		const alDia = ["356", "473", "635", "244", "588", "870", "883", "679"];
		const porVencer = ["635", "263", "346", "356", "619", "588", "883", "870"];
		
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

		$("#chart-medidor-gral").insertFusionCharts({
			type: "angulargauge",
			width: "100%",
			height: "100%",
			dataFormat: "json",
			dataSource: {
				chart: {
					caption: "Requerimientos en General",
					lowerlimit: "0",
					upperlimit: "100",
					showvalue: "1",
					// numbersuffix: "%",
					theme: "fusion",
					exportEnabled: "1",
					showtooltip: "0",
				},
				colorrange: {
				color: [
					{
					minvalue: "0",
					maxvalue: "50",
					code: "#F2726F"
					},
					{
					minvalue: "50",
					maxvalue: "75",
					code: "#FFC533"
					},
					{
					minvalue: "75",
					maxvalue: "100",
					code: "#62B58F"
					}
				]
				},
				dials: {
				dial: [
					{
					value: "62"
					}
				]
				}
			}
		});

		for(var i=0; i<8; i++) {
			$("#chart-medidor-eq"+i).insertFusionCharts({
				type: "angulargauge",
				width: "100%",
				height: "100%",
				dataFormat: "json",
				dataSource: {
					chart: {
						caption: equipos[i],
						lowerlimit: "0",
						upperlimit: "100",
						showvalue: "1",
						theme: "fusion",
						showtooltip: "0",
					},
					colorrange: {
					color: [
						{
						minvalue: "0",
						maxvalue: "50",
						code: "#F2726F"
						},
						{
						minvalue: "50",
						maxvalue: "75",
						code: "#FFC533"
						},
						{
						minvalue: "75",
						maxvalue: "100",
						code: "#62B58F"
						}
					]
					},
					dials: {
					dial: [
						{
						value: "62"
						}
					]
					}
				}
			});
		}
    </script>
@endsection
