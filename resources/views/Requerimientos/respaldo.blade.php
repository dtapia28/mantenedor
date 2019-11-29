    <p>
    	@if ($resolutor->fechaRealCierre != "")
    		<strong>Mes de cierre real: </strong> {{date('n', strtotime($requerimiento->fechaRealCierre)).strftime("%e") }}
    	@else
    		<strong>Mes de cierre: </strong>{{ date('n', strtotime($requerimiento->fechaCierre)) }}
    	@endif    	
    </p>
    <p><strong>Días laborales: </strong> {{ $hastaCierre }}</p>
    <p><strong>Días transcurridos: </strong> {{ $hastaHoy }}</p>
    <p><strong>Días restantes: </strong> {{ $restantes }}</p>
    <p><strong>Avance diario: </strong>{{ number_format(100/$hastaCierre, 2, ',', '.') }}%</p>
    	@if ($fechaCierre<=$hoy)
    		    <p><strong>Avance esperado: </strong>100%</p>
    	@else
    		<p><strong>Avance esperado: </strong>{{ (100/$hastaCierre)*$hastaHoy }}%</p>
    	@endif	                    