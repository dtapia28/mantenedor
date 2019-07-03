<?php

namespace App\Exports;

use App\Requerimiento;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;


class RequerimientosExport implements FromQuery
{
    /**
    * @return \Illuminate\Support\Collection
    */

    use Exportable;
    private $estado;
    private $resolutor;

    public function __construct($estado, $resolutor=null)
    {
    	$this->estado = $estado;

    	$this->resolutor = $resolutor;
    }

    public function query()
    {

        return Requerimiento::query()
        	->where([
        		['estado', $this->estado],
        		if ($this->resolutor != null) {
        			['resolutor', $this->resolutor],
        		}
        	])->whereNotNull('resolutor');
        	
    }
}
