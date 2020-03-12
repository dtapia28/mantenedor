@section('css')
	<link href="{{ asset('vendor/DataTables/datatables.min.css') }}" rel="stylesheet" />
@endsection

<div class="row">
  	<div class="col-lg-8">
		<div class="ibox">
			<div class="ibox-body" align="center">
				<div id="chart-dona" style="min-height:45vh"></div>
				<!-- Modal -->
				<div class="modal fade" id="dataModalDona" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
					<div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="exampleModalScrollableTitle">Requerimientos <span id="estadoModalDona"></span> del Resolutor</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<div class="table-responsive">
									<table class="table table-striped table-bordered table-sm" id="tablaModalDona" style="font-size: 0.85em">
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
						<?php
							$abiertosTab = $data["abiertos"];
							$cerradosTab = $data["cerrados"];
							$vencidosTab = $data["vencido"];
							$vencerTab   = $data["vencer"];
							$totales = $data["abiertos"] + $data["cerrados"] + $data["vencido"] + $data["vencer"];
							$totales == 0 ? $totales = 1 : $totales = $totales;
							$abiertosPorc = number_format(($data["abiertos"] * 100 / $totales), 2, ',', '.');
							$cerradosPorc = number_format(($data["cerrados"] * 100 / $totales), 2, ',', '.');
							$vencidosPorc = number_format(($data["vencido"] * 100 / $totales), 2, ',', '.');
							$vencerPorc = number_format(($data["vencer"] * 100 / $totales), 2, ',', '.');

							$abiertosPorc2 = number_format(($data["abiertos"] * 100 / $totales), 0, ',', '.');
							$cerradosPorc2 = number_format(($data["cerrados"] * 100 / $totales), 0, ',', '.');
							$vencidosPorc2 = number_format(($data["vencido"] * 100 / $totales), 0, ',', '.');
							$vencerPorc2 = number_format(($data["vencer"] * 100 / $totales), 0, ',', '.');
						?>
						<tbody>
							<tr>
								<td>Abiertos</td>
								<td><?=$abiertosTab?></td>
								<td>
									<div class="progress">
										<div class="progress-bar progress-bar-success" role="progressbar" style="width:<?=$abiertosPorc2?>%; height:5px;" aria-valuenow="" aria-valuemin="0" aria-valuemax="100"></div>
									</div>
									<span class="progress-parcent"><?=$abiertosPorc?>%</span>
								</td>
							</tr>
							<tr>
								<td>Cerrados</td>
								<td><?=$cerradosTab?></td>
								<td>
									<div class="progress">
										<div class="progress-bar progress-bar-danger" role="progressbar" style="width:<?=$cerradosPorc2?>%; height:5px;" aria-valuenow="" aria-valuemin="0" aria-valuemax="100"></div>
									</div>
									<span class="progress-parcent"><?=$cerradosPorc?>%</span>
								</td>
							</tr>
							<tr>
								<td>Vencidos</td>
								<td><?=$vencidosTab?></td>
								<td>
									<div class="progress">
										<div class="progress-bar progress-bar-warning" role="progressbar" style="width:<?=$vencidosPorc2?>%; height:5px;" aria-valuenow="" aria-valuemin="0" aria-valuemax="100"></div>
									</div>
									<span class="progress-parcent"><?=$vencidosPorc?>%</span>
								</td>
							</tr>
							<tr>
								<td>Por Vencer</td>
								<td><?=$vencerTab?></td>
								<td>
									<div class="progress">
										<div class="progress-bar progress-bar-info" role="progressbar" style="width:<?=$vencerPorc2?>%; height:5px;" aria-valuenow="" aria-valuemin="0" aria-valuemax="100"></div>
									</div>
									<span class="progress-parcent"><?=$vencerPorc?>%</span>
								</td>
							</tr>
							<tr>
								<td><strong>TOTALES</strong></td>
								<td><strong><?=$totales?></strong></td>
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
				<!-- Modal -->
				<div class="modal fade" id="dataModalSol" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
					<div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="exampleModalScrollableTitle">Requerimientos <span id="estadoModalSol"></span> del Solicitante <span id="solicitanteModalSol"></span></h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<div class="table-responsive">
									<table class="table table-striped table-bordered table-sm" id="tablaModalSol" style="font-size: 0.85em">
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
				<!-- Modal -->
				<div class="modal fade" id="dataModalRes" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
					<div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="exampleModalScrollableTitle">Requerimientos <span id="estadoModalRes"></span> del Resolutor <span id="solicitanteModalRes"></span></h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<div class="table-responsive">
									<table class="table table-striped table-bordered table-sm" id="tablaModalRes" style="font-size: 0.85em">
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
	</div>
</div>
@section('scripts_dash')
	<script src="{{ asset('vendor/DataTables/datatables.min.js') }}" type="text/javascript"></script>
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
		
		// Gráfico de dona 
		const titiloequipo = 'Equipo <?=$data["equipo"]?>';
		$("#chart-dona").insertFusionCharts({
			type: "doughnut3d",
			width: "100%",
			height: "100%",
			dataFormat: "json",
			dataSource: {
				chart: {
					caption: titiloequipo,
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
					label: "Al día",
					value: <?=$data["alDia"]?>,
					color: "#2ecc71",
				},
				{
					label: "Por Vencer",
					value: <?=$data["vencer"]?>,
					color: "#f39c12",
				},
				{
					label: "Vencidos",
					value: <?=$data["vencido"]?>,
					color: "#e74c3c",
				}
				]
			},
			events: {
				dataPlotClick: function(e) {
					var estado = e.data.categoryLabel;
					var valor = e.data.dataValue;
					var codEstado;
					
					$("#dataModalDona").modal("show");
					$("#estadoModalDona").text(estado);
					switch(estado) {
						case 'Al día': codEstado = 1; break;
						case 'Por Vencer': codEstado = 2; break;
						case 'Vencidos': codEstado = 3; break;
					}
					$.ajax({
						type: 'post',
						url: 'dashboard/getReqResolutorGralByEstado',
						data: {
							'estado': codEstado
						},
						dataType: 'json',
						success: function (data) {
							if (data.respuesta) {
								$('#tablaModalDona').DataTable().destroy();
								$("#tablaModalDona tbody tr").remove();
								for(var i=0; i<data.req.length; i++) {
									var fila = "<tr><td style='white-space: nowrap;'><a href='requerimientos/" + data.req[i]['id'] + "'>" + data.req[i]['id2'] + "</a></td><td>" + data.req[i].textoRequerimiento + "</td><td>" + data.req[i].fechaSolicitud + "</td><td>" + data.req[i].fechaCierreF + "</td><td>" + data.req[i].nombreResolutor + "</td><td>" + data.req[i].porcentajeEjecutado + "</td></tr>";
									$("#tablaModalDona tbody").append(fila);
								}
							} else {
								console.log("El resolutor no tiene registros de requerimientos");
								return;
							}
						},
						complete: function() {
							$('#tablaModalDona').DataTable({
								"language": {
									"url": "{{ asset('vendor/DataTables/lang/spanish.json') }}"
								},
								pageLength: 10,
								stateSave: true,
							});
						},
						error: function (data) {
							console.log('Error:', data);
							alert("Error al consultar los requerimientos del resolutor");
						}
					});
				}
			}
		});

		// Gráfico apilado por solicitante
		<?php
			$solicitantes = "";
			foreach((array)$data["arraySolicitantes"] as $item) { $solicitantes .= "'".$item."',"; }
			$solicitantes = substr($solicitantes, 0, strlen($solicitantes)-1);
			$vencidos = "";
			foreach((array)$data["porSolicitanteVencido"] as $item) { $vencidos .= "'".$item."',"; }
			$vencidos = substr($vencidos, 0, strlen($vencidos)-1);
			$alDia = "";
			foreach((array)$data["porSolicitanteAldia"] as $item) { $alDia .= "'".$item."',"; }
			$alDia = substr($alDia, 0, strlen($alDia)-1);
			$porVencer = "";
			foreach((array)$data["porSolicitantePorVencer"] as $item) { $porVencer .= "'".$item."',"; }
			$porVencer = substr($porVencer, 0, strlen($porVencer)-1);
		?>
		const solicitantes = [<?=$solicitantes?>];
		const vencidos = [<?=$vencidos?>];
		const alDia = [<?=$alDia?>];
		const porVencer = [<?=$porVencer?>];
		
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
			},
			events: {
				dataPlotClick: function(e) {
					var solicitante = e.data.categoryLabel;
					var estado = e.data.datasetName;
					var valor = e.data.dataValue;
					var codEstado;

					$("#dataModalSol").modal("show");
					$("#solicitanteModalSol").text(solicitante);
					$("#estadoModalSol").text(estado);
					switch(estado) {
						case 'Al día': codEstado = 1; break;
						case 'Por Vencer': codEstado = 2; break;
						case 'Vencidos': codEstado = 3; break;
					}
					$.ajax({
						type: 'post',
						url: 'dashboard/getReqSolicitanteByEstado',
						data: {
							'solicitante': solicitante,
							'estado': codEstado,
							'rango_fecha': $("#rango_fecha").val(),
							'desde': $("#fec_des").val(),
							'hasta': $("#fec_has").val(),
						},
						dataType: 'json',
						success: function (data) {
							if (data.respuesta) {
								$('#tablaModalSol').DataTable().destroy();
								$("#tablaModalSol tbody tr").remove();
								for(var i=0; i<data.req.length; i++) {
									var fila = "<tr><td style='white-space: nowrap;'><a href='requerimientos/" + data.req[i]['id'] + "'>" + data.req[i]['id2'] + "</a></td><td>" + data.req[i].textoRequerimiento + "</td><td>" + data.req[i].fechaSolicitud + "</td><td>" + data.req[i].fechaCierreF + "</td><td>" + data.req[i].nombreResolutor + "</td><td>" + data.req[i].porcentajeEjecutado + "</td></tr>";
									$("#tablaModalSol tbody").append(fila);
								}
							} else {
								console.log("El solicitante no tiene registros de requerimientos");
								return;
							}
						},
						complete: function() {
							$('#tablaModalSol').DataTable({
								"language": {
									"url": "{{ asset('vendor/DataTables/lang/spanish.json') }}"
								},
								pageLength: 10,
								stateSave: true,
							});
						},
						error: function (data) {
							console.log('Error:', data);
							alert("Error al consultar los requerimientos del solicitante");
						}
					});
				}
			}
		});

		// Gráfico Medidor
    	$("#chart-medidor").insertFusionCharts({
			type: "angulargauge",
			width: "100%",
			height: "100%",
			dataFormat: "json",
			dataSource: {
				chart: {
					caption: titiloequipo,
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
					value: <?=$data["porcentajeAlDia"]?>,
					}
				]
				}
			}
		});

		// Gráfico apilado por resolutor
		<?php
			$resolutores = "";
			foreach((array)$data["arrayResolutores"] as $item) { $resolutores .= "'".$item."',"; }
			$resolutores = substr($resolutores, 0, strlen($resolutores)-1);
			$vencidosR = "";
			foreach((array)$data["porResolutorVencido"] as $item) { $vencidosR .= "'".$item."',"; }
			$vencidosR = substr($vencidosR, 0, strlen($vencidosR)-1);
			$alDiaR = "";
			foreach((array)$data["porResolutorAlDia"] as $item) { $alDiaR .= "'".$item."',"; }
			$alDiaR = substr($alDiaR, 0, strlen($alDiaR)-1);
			$porVencerR = "";
			foreach((array)$data["porResolutorPorVencer"] as $item) { $porVencerR .= "'".$item."',"; }
			$porVencerR = substr($porVencerR, 0, strlen($porVencerR)-1);
		?>
		const resolutores = [<?=$resolutores?>];
		const vencidosR = [<?=$vencidosR?>];
		const alDiaR = [<?=$alDiaR?>];
		const porVencerR = [<?=$porVencerR?>];
		
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
			},
			events: {
				dataPlotClick: function(e) {
					var resolutor = e.data.categoryLabel;
					var estado = e.data.datasetName;
					var valor = e.data.dataValue;
					var codEstado;

					$("#dataModalRes").modal("show");
					$("#solicitanteModalRes").text(resolutor);
					$("#estadoModalRes").text(estado);
					switch(estado) {
						case 'Al día': codEstado = 1; break;
						case 'Por Vencer': codEstado = 2; break;
						case 'Vencidos': codEstado = 3; break;
					}
					$.ajax({
						type: 'post',
						url: 'dashboard/getReqResolutorByEstado',
						data: {
							'resolutor': resolutor,
							'estado': codEstado,
							'rango_fecha': $("#rango_fecha").val(),
							'desde': $("#fec_des").val(),
							'hasta': $("#fec_has").val(),
						},
						dataType: 'json',
						success: function (data) {
							if (data.respuesta) {
								$('#tablaModalRes').DataTable().destroy();
								$("#tablaModalRes tbody tr").remove();
								for(var i=0; i<data.req.length; i++) {
									var fila = "<tr><td style='white-space: nowrap;'><a href='requerimientos/" + data.req[i]['id'] + "'>" + data.req[i]['id2'] + "</a></td><td>" + data.req[i].textoRequerimiento + "</td><td>" + data.req[i].fechaSolicitud + "</td><td>" + data.req[i].fechaCierreF + "</td><td>" + data.req[i].nombreResolutor + "</td><td>" + data.req[i].porcentajeEjecutado + "</td></tr>";
									$("#tablaModalRes tbody").append(fila);
								}
							} else {
								console.log("El resolutor no tiene registros de requerimientos");
								return;
							}
						},
						complete: function() {
							$('#tablaModalRes').DataTable({
								"language": {
									"url": "{{ asset('vendor/DataTables/lang/spanish.json') }}"
								},
								pageLength: 10,
								stateSave: true,
							});
						},
						error: function (data) {
							console.log('Error:', data);
							alert("Error al consultar los requerimientos del resolutor");
						}
					});
				}
			}
		});
    </script>
@endsection
