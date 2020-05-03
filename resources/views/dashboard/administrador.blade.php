@section('css')
	<link href="{{ asset('vendor/DataTables/Excel/datatables.min.css') }}" rel="stylesheet" />
@endsection

<div class="ibox">
	<div class="ibox-body" align="center">
		<div id="chart-apilado" style="min-height:60vh"></div>
		<!-- Modal -->
		<div class="modal fade" id="dataModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
			<div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalScrollableTitle">Requerimientos <span id="estadoModal"></span> del Equipo <span id="equipoModal"></span></h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="table-responsive">
							<table class="table table-striped table-bordered table-sm" id="tablaModal" style="font-size: 0.85em">
								<thead>
									<tr>
										<th>Id</th>
										<th>Requerimiento</th>
										<th>F. Solicitud</th>
										<th>F. Cierre</th>
										<th>Resolutor</th>
										<th>% Avance</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
					</div>
				</div>
			</div>
		</div>
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
			@for ($i=0; $i<count((array)$data["arrayEquipos"]); $i++)
			<div class="col-md-3">
				<div id="chart-medidor-eq{{$i}}" style="min-height:30vh;"></div>
			</div>
			@endfor
		</div>
	</div>
</div>

@section('scripts_dash')
	<script src="{{ asset('vendor/DataTables/Excel/datatables.min.js') }}" type="text/javascript"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$.ajaxSetup({
				headers:
				{ 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
			});
			@isset($data)
				@if ($data['rango_fecha'] == "por_rango")
					$('#fec_des').prop('disabled', false);
					$('#fec_des').val('<?= substr($data["desde"], 8, 2)."/".substr($data["desde"], 5, 2)."/".substr($data["desde"], 0, 4) ?>');
					$('#fec_has').prop('disabled', false);
					$('#fec_has').val('<?= substr($data["hasta"], 8, 2)."/".substr($data["hasta"], 5, 2)."/".substr($data["hasta"], 0, 4) ?>');
				@endif
			@endisset
		});
		<?php
			$equipos = "";
			foreach((array)$data["arrayEquipos"] as $item) { $equipos .= "'".$item."',"; }
			$equipos = substr($equipos, 0, strlen($equipos)-1);
			$vencidos = "";
			foreach((array)$data["arrayVencidos"] as $item) { $vencidos .= "'".$item."',"; }
			$vencidos = substr($vencidos, 0, strlen($vencidos)-1);
			$alDia = "";
			foreach((array)$data["arrayAlDia"] as $item) { $alDia .= "'".$item."',"; }
			$alDia = substr($alDia, 0, strlen($alDia)-1);
			$porVencer = "";
			foreach((array)$data["arrayPorVencer"] as $item) { $porVencer .= "'".$item."',"; }
			$porVencer = substr($porVencer, 0, strlen($porVencer)-1);
		?>
		const equipos = [<?=$equipos?>];
		const vencidos = [<?=$vencidos?>];
		const alDia = [<?=$alDia?>];
		const porVencer = [<?=$porVencer?>];
		
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
		// Gráfico Apilado por Equipo
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
			},
			events: {
				dataPlotClick: function(e) {
					var equipo = e.data.categoryLabel;
					var estado = e.data.datasetName;
					var valor = e.data.dataValue;
					var codEstado;
					$("#dataModal").modal("show");
					$("#equipoModal").text(equipo);
					$("#estadoModal").text(estado);
					switch(estado) {
						case 'Al día': codEstado = 1; break;
						case 'Por Vencer': codEstado = 2; break;
						case 'Vencidos': codEstado = 3; break;
					}
					$.ajax({
						type: 'post',
						url: 'dashboard/getReqEquipoByEstado',
						data: {
							'equipo': equipo,
							'estado': codEstado,
							'rango_fecha': $("#rango_fecha").val(),
							'desde': $("#fec_des").val(),
							'hasta': $("#fec_has").val(),
						},
						dataType: 'json',
						success: function (data) {
							if (data.respuesta) {
								$('#tablaModal').DataTable().destroy();
								$("#tablaModal tbody tr").remove();
								for(var i=0; i<data.req.length; i++) {
									var fila = "<tr><td style='white-space: nowrap;'><a href='requerimientos/" + data.req[i]['id'] + "'>" + data.req[i]['id2'] + "</a></td><td>" + data.req[i].textoRequerimiento + "</td><td>" + data.req[i].fechaSolicitud + "</td><td>" + data.req[i].fechaCierreF + "</td><td>" + data.req[i].nombreResolutor + "</td><td>" + data.req[i].porcentajeEjecutado + "</td></tr>";
									$("#tablaModal tbody").append(fila);
								}
							} else {
								console.log("El equipo no tiene registros de requerimientos");
								return;
							}
						},
						complete: function() {
							$('#tablaModal').DataTable({
								"dom": '<"row"<"col-md-6 text-left"l><"col-md-6 text-right"f>><rt><"row"<"col-md-4 d-flex"p><"col-md-4 text-center"B><"col-md-4 text-right"i>>',
								"language": {
									"url": "{{ asset('vendor/DataTables/lang/spanish.json') }}"
								},
								pageLength: 10,
								stateSave: true,
								"buttons": [
									{
										"extend": 'excelHtml5',
										"className": 'btn btn-success',
										"text": '<i class="fa fa-file-excel-o"></i> XLS',
										"messageTop": 'Requerimientos por Equipo',
										"exportOptions": {
											"modifier": {
												"page": 'all'
											}
										}
									},
								],
							});
						},
						error: function (data) {
							console.log('Error:', data);
							alert("Error al consultar los requerimientos del equipo");
						}
					});
				}
			}
		});
		// Gráfico Medidor General
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
					value: "<?=$data['porcentajeAlDia']?>"
					}
				]
				}
			}
		});
		// Gráfico Medidores por Equipo
		var numEquipos = <?=count((array)$data["arrayEquipos"])?>;
		<?php
			$cerradosAlDiaPorEq = "";
                            foreach((array)$data["porcentajeEquipoAlDia"] as $item) {$cerradosAlDiaPorEq .= "'".$item."',"; }
			$cerradosAlDiaPorEq = substr($cerradosAlDiaPorEq, 0, strlen($cerradosAlDiaPorEq)-1);
		?>
		var cerradosAlDiaPorEq = [<?=$cerradosAlDiaPorEq?>];
		for(var i=0; i<numEquipos; i++) {
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
						value: cerradosAlDiaPorEq[i]
						}
					]
					}
				}
			});
		}
    </script>
@endsection
