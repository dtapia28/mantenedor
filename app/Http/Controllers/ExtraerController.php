<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\EstadoExport;
use App\Exports\EjecutadoExport;
use App\Exports\CambiosExport;
use App\Exports\SolicitantesExport;
use App\Solicitante;

class ExtraerController extends Controller
{

    public function index()
    {

    	$solicitantes = Solicitante::where([
    		['rutEmpresa', auth()->user()->rutEmpresa],
    	])->get();
    
        return view('Extraer.index', compact('solicitantes'));
    }   

    public function porEstado(Request $request)
    {
    	return (new EstadoExport($request['estado']))->download('requerimientosPorEstado.xlsx');
    } 

    public function porEjecutado(Request $request)
    {
    	return (new EjecutadoExport($request['porcentaje'], $request['comparacion']))->download('porPorcentaje.xlsx');
    }

    public function cambios(Request $request)
    {
    	return (new CambiosExport($request['cambios'], $request['comparacion']))->download('porCambios.xlsx');
    }

    public function solicitantes(Request $request)
    {
    	return (new SolicitantesExport($request['idSolicitante']))->download('porSolicitante.xlsx');
    }
}
