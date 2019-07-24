<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\EstadoExport;
use App\Exports\EjecutadoExport;
use App\Exports\CambiosExport;
use App\Exports\SolicitantesExport;
use App\Exports\ResolutorsExport;
use App\Solicitante;
use App\Resolutor;
use App\Requerimiento;
use Illuminate\Support\Facades\DB;

class ExtraerController extends Controller
{

    public function index()
    {

    	$solicitantes = Solicitante::where([
    		['rutEmpresa', auth()->user()->rutEmpresa],
    	])->get();

        $resolutors = Resolutor::where([
            ['rutEmpresa', auth()->user()->rutEmpresa],
        ])->get();
    
        return view('Extraer.index', compact('solicitantes', 'resolutors'));
    }   

    public function porEstado(Request $request)
    {

        $requerimientos = Requerimiento::where([
            ['estado', $request['estado']],
            ['rutEmpresa', auth()->user()->rutEmpresa],
        ])->get(['id', 'textoRequerimiento', 'fechaEmail', 'fechaCierre'])->toArray();


        return view('Extraer.index', compact('requerimientos'));
    } 

    public function porEjecutado(Request $request)
    {

        if ($request['comparacion'] == 1) {
            $requerimientos = Requerimiento::where([
                ['porcentajeEjecutado', '<=', $request['porcentaje']],
                ['rutEmpresa', auth()->user()->rutEmpresa],               
            ])->get(['id', 'textoRequerimiento', 'fechaEmail', 'fechaCierre', 'porcentajeEjecutado'])->toArray();
        } else {
            $requerimientos = Requerimiento::where([
                ['porcentajeEjecutado', '>', $request['porcentaje']],
                ['rutEmpresa', auth()->user()->rutEmpresa],                
            ])->get(['id', 'textoRequerimiento', 'fechaEmail', 'fechaCierre', 'porcentajeEjecutado'])->toArray();
        }
    	
        return view('Extraer.index', compact('requerimientos'));
    }

    public function cambios(Request $request)
    {
        if ($request['comparacion'] == 1) {
            $requerimientos = Requerimiento::where([
                ['numeroCambios', '<=', $request['cambios']],
                ['rutEmpresa', auth()->user()->rutEmpresa],               
            ])->get(['id', 'textoRequerimiento', 'fechaEmail', 'fechaCierre', 'porcentajeEjecutado'])->toArray();
        } else {
            $requerimientos = Requerimiento::where([
                ['numeroCambios', '>', $request['cambios']],
                ['rutEmpresa', auth()->user()->rutEmpresa],                
            ])->get(['id', 'textoRequerimiento', 'fechaEmail', 'fechaCierre', 'porcentajeEjecutado'])->toArray();
        }
        
        return view('Extraer.index', compact('requerimientos'));
    }

    public function solicitantes(Request $request)
    {
    	return (new SolicitantesExport($request['idSolicitante']))->download('porSolicitante.xlsx');
    }

    public function resolutors(Request $request)
    {
        return (new ResolutorsExport($request['idResolutor']))->download('porResolutor.xlsx');
    }
}
