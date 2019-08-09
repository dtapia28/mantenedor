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

        $solicitantes = Solicitante::where('rutEmpresa', auth()->user()->rutEmpresa)->orderBy('nombreSolicitante')->get();

        $resolutors = Resolutor::where([
            ['rutEmpresa', auth()->user()->rutEmpresa],
        ])->get();
    
        return view('Extraer.index', compact('solicitantes', 'resolutors'));
    }   

    public function porEstado(Request $request)
    {

        $base = DB::table('porestado_view')->where([
            ['estado', $request['estado']],
            //['rutEmpresa', auth()->user()->rutEmpresa],
        ])->get(['id', 'textoRequerimiento AS Texto del requerimiento', 'fechaEmail AS Fecha de Email', 'fechaSolicitud AS Fecha de solicitud', 'fechaCierre AS Fecha de cierre', 'porcentajeEjecutado AS Porcentaje ejecutado', 'nombreSolicitante AS Solicitante', 'nombreResolutor AS Resolutor', 'nameTeam AS Equipo', 'namePriority AS Prioridad'])->toArray();
        $base2 = [];
        $requerimientos = [];
        for ($i=0; $i < count($base); $i++) {
            $base2 = (array) $base[$i];
            array_push($requerimientos, $base2);
        }


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

        dd($requerimientos);
    	
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
        if ($request['idSolicitante'] != "") {
            $requerimientos = Requerimiento::where([
                ['rutEmpresa', auth()->user()->rutEmpresa],
                ['idSolicitante', $request['idSolicitante']],
            ])->get(['id', 'textoRequerimiento', 'fechaEmail', 'fechaCierre', 'porcentajeEjecutado'])->toArray();
        }

        return view('Extraer.index', compact('requerimientos'));
    }

    public function resolutors(Request $request)
    {
        if ($request['idResolutor'] != "") {
            $requerimientos = Requerimiento::where([
                ['rutEmpresa', auth()->user()->rutEmpresa],
                ['resolutor', $request['idResolutor']],
            ])->get(['id', 'textoRequerimiento', 'fechaEmail', 'fechaCierre', 'porcentajeEjecutado'])->toArray();            
        }

        return view('Extraer.index', compact('requerimientos'));        
    }
}
