<div class="row">
  	<div class="col-lg-8">
		<div class="ibox">
			<div class="ibox-body" align="center">
				<div id="chart-dona" style="min-height:45vh"></div>
			</div>
		</div>
	</div>
	<div class="col-lg-4">
		<div class="ibox">
			<div class="ibox-body" align="center">
				<div style="min-height:47vh">
					<table class="table table-striped m-t-20 visitors-table">
						<thead>
							<tr>
								<th>Estatus</th>
								<th>Cantidad</th>
								<th>Global</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Abiertos</td>
								<td>300</td>
								<td>
									<div class="progress">
										<div class="progress-bar progress-bar-success" role="progressbar" style="width:33%; height:5px;" aria-valuenow="33" aria-valuemin="0" aria-valuemax="100"></div>
									</div>
									<span class="progress-parcent">33%</span>
								</td>
							</tr>
							<tr>
								<td>Cerrados</td>
								<td>250</td>
								<td>
									<div class="progress">
										<div class="progress-bar progress-bar-warning" role="progressbar" style="width:28%; height:5px;" aria-valuenow="28" aria-valuemin="0" aria-valuemax="100"></div>
									</div>
									<span class="progress-parcent">28%</span>
								</td>
							</tr>
							<tr>
								<td>Vencidos</td>
								<td>200</td>
								<td>
									<div class="progress">
										<div class="progress-bar progress-bar-danger" role="progressbar" style="width:22%; height:5px;" aria-valuenow="22" aria-valuemin="0" aria-valuemax="100"></div>
									</div>
									<span class="progress-parcent">22%</span>
								</td>
							</tr>
							<tr>
								<td>Por Vencer</td>
								<td>150</td>
								<td>
									<div class="progress">
										<div class="progress-bar progress-bar-info" role="progressbar" style="width:17%; height:5px;" aria-valuenow="17" aria-valuemin="0" aria-valuemax="100"></div>
									</div>
									<span class="progress-parcent">17%</span>
								</td>
							</tr>
							<tr>
								<td><strong>TOTALES</strong></td>
								<td><strong>900</strong></td>
								<td>
									<div class="progress">
										<div class="progress-bar progress-bar" role="progressbar" style="width:100%; height:5px;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
									</div>
									<span class="progress-parcent"><strong>100%</strong></span>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-lg-8">
		<div class="ibox">
			<div class="ibox-body" align="center">
				<div id="chart-apilado" style="min-height:50vh"></div>
			</div>
		</div>
	</div>
	<div class="col-lg-4">
		<div class="ibox">
			<div class="ibox-body" align="center">
			  	<div id="chart-medidor" style="min-height:50vh;"></div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox">
			<div class="ibox-body" align="center">
				<div id="chart-apilado2" style="min-height:50vh"></div>
			</div>
		</div>
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
					caption: "Requerimientos del Resolutor",
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

		const solicitantes = ["Pedro Pérez", "Marcos Jiménez", "Luis Villarroel", "María Maza"];
		const vencidos = ["357", "267", "555", "679"];
		const alDia = ["356", "883", "306", "679"];
		const porVencer = ["635", "263", "346", "870"];
		
		var nombreSolicitantes = [];
		solicitantes.forEach(element => {
			var obj = {};
			obj.label = element;
			nombreSolicitantes.push(obj);
			return nombreSolicitantes;
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
					caption: "Requerimientos por Solicitante",
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
					category: nombreSolicitantes
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

    	$("#chart-medidor").insertFusionCharts({
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

		const resolutores = ["Carlos Martínez", "José Sánchez", "Jimena Marcano", "Magalis Carrillo", "Elezar Ramírez", "Andrés Celedon"];
		const vencidosR = ["457", "367", "355", "325", "479", "223"];
		const alDiaR = ["256", "213", "806", "779", "621", "436"];
		const porVencerR = ["435", "563", "246", "570", "310", "329"];
		
		var nombreResolutores = [];
		resolutores.forEach(element => {
			var obj = {};
			obj.label = element;
			nombreResolutores.push(obj);
			return nombreResolutores;
		});

		var valoresVencidosR = [];
		vencidosR.forEach(element => {
			var obj = {};
			obj.value = element;
			valoresVencidosR.push(obj);
			return valoresVencidosR;
		});

		var valoresAlDiaR = [];
		alDiaR.forEach(element => {
			var obj = {};
			obj.value = element;
			valoresAlDiaR.push(obj);
			return valoresAlDiaR;
		});

		var valoresPorVencerR = [];
		porVencerR.forEach(element => {
			var obj = {};
			obj.value = element;
			valoresPorVencerR.push(obj);
			return valoresPorVencerR;
		});
		
		$("#chart-apilado2").insertFusionCharts({
			type: "stackedcolumn3d",
			width: "100%",
			height: "100%",
			dataFormat: "json",
			dataSource: {
				chart: {
					caption: "Requerimientos por Resolutor",
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
					category: nombreResolutores
				}
				],
				dataset: [
				{
					seriesname: "Vencidos",
					data: valoresVencidosR,
					color: "#e74c3c",
				},
				{
					seriesname: "Por Vencer",
					data: valoresPorVencerR,
					color: "#f39c12",
				},
				{
					seriesname: "Al día",
					data: valoresAlDiaR,
					color: "#2ecc71",
				}
				]
			}
		});
    </script>
@endsection
