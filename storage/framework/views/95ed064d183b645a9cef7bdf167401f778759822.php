
<?php
if (! empty($requerimientos)) {

	$pruebas = $requerimientos;
	$filename = "exportacion.xls";
	header("Content-Type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=\"$filename\"");
	$isPrintHeader = false;

		foreach ($pruebas as $row) {

			if (! $isPrintHeader) {

				echo implode("\t", array_keys($row)) . "\n";
				$isPrintHeader = true;

			}
			echo implode("\t", array_values($row)) . "\n";

		}

	exit();

}

?>
<table>
	<thead>
		<tr>
	    	<th>Id</th>
	        <th>Requerimiento</th>
	    </tr>  
	</thead>
	<tbody>      	
		<?php
		while ($ro=mysli_fetch_assoc($pruebas)) {
		?>
			<tr>
				<td><?php echo $ro[$key]["id"]; ?></td>
				<td><?php echo $ro[$key]["textoRequerimiento"]; ?></td>

			</tr>
		<?php		
			}
		?>
	</tbody>	
</table>

<div class="btn">
	<form action="<?php echo e(url('/extracciones/estado')); ?>" method="GET">
		<button type="submit" id="btnExport" class="btn btn-info">Exportar</button>
	</form>
</div>

</body>
</html><?php /**PATH C:\xampp\htdocs\Mantenedor\resources\views/Extraer/ver.blade.php ENDPATH**/ ?>