<?php

namespace App\Exports;

use App\Requerimiento;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;


class SolicitantesExport implements WithHeadings, FromCollection
{

	use Exportable;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct(int $solicitante)
    {
    	$this->rutEmpresa = auth()->user()->rutEmpresa;
    	$this->solicitante = $solicitante;
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
        return Requerimiento::where([
        	['rutEmpresa', auth()->user()->rutEmpresa],
        	['idSolicitante', $this->solicitante],
        ])->select('id', 'textoRequerimiento', 'fechaEmail', 'fechaSolicitud', 'fechaCierre', 'numeroCambios', 'porcentajeEjecutado')->get();
    }
}
