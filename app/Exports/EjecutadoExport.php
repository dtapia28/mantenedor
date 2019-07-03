<?php

namespace App\Exports;

use App\Requerimiento;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;


class EjecutadoExport implements WithHeadings, FromCollection
{

	use Exportable;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct(int $porcentaje, int $comparacion)
    {
    	$this->rutEmpresa = auth()->user()->rutEmpresa;
    	$this->porcentaje = $porcentaje;
    	$this->comparacion = $comparacion;
    }

    public function headings(): array
    {
        return [
            'id',
            'Texto de requerimiento',
            'Fecha de Email',
            'Fecha de Solicitud',
            'Fecha de Cierre',
            'Número de cambios',
            'Porcentaje ejecutado',           
        ];    	
    }

    public function collection()
    {
    	if ($this->comparacion == 1) {
	        return Requerimiento::where([
	        	['rutEmpresa', $this->rutEmpresa],
	        	['porcentajeEjecutado', '<=', $this->porcentaje],
	        	['estado', 1],
	        ])->select('id', 'textoRequerimiento', 'fechaEmail', 'fechaSolicitud', 'fechaCierre', 'numeroCambios', 'porcentajeEjecutado')->get();    		
    	} else {
	        return Requerimiento::where([
	        	['rutEmpresa', $this->rutEmpresa],
	        	['porcentajeEjecutado', '>', $this->porcentaje],
	        	['estado', 1],
	        ])->select('id', 'textoRequerimiento', 'fechaEmail', 'fechaSolicitud', 'fechaCierre', 'numeroCambios', 'porcentajeEjecutado')->get();    		
    	}

    }
}
