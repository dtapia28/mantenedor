<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Requerimiento;
use App\Solicitante;
use App\Team;
use DateTime;

class GraficosSolicitanteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Cantidad de requerimientos de solicitante al día, por vencer, vencido
        $solicitante = Solicitante::where('idUser', auth()->user()->id)->first();
        $req = DB::table('requerimientos_equipos')->where([
            ['rutEmpresa', auth()->user()->rutEmpresa],
            ['estado', 1],
            ['aprobacion', 3],
            ['idSolicitante', $solicitante->id],
        ])->get();
        $alDia = 0;
        $vencer = 0;
        $vencido = 0;
        $arrayEquipo = [];
        foreach ($req as $requerimiento) 
        {
            $requerimiento=(array)$requerimiento;
            $arrayEquipo[]=$requerimiento['idEquipo'];
            if ($requerimiento['fechaCierre'] == "9999-12-31 00:00:00") {
                $requerimiento ['status'] = 1;
                $alDia++;
                $requerimiento = (object) $requerimiento;
                $requerimientos [] = $requerimiento;
            } else
            {
                $hoy = new DateTime();
                $cierre = new DateTime($requerimiento['fechaCierre']);
                if ($cierre->getTimestamp()<$hoy->getTimestamp()) {
                    $requerimiento ['status'] = 3;
                    $vencido++;
                } else {
                    $variable = 0;
                    while ($hoy->getTimestamp() < $cierre->getTimestamp()) {                                        
                        if ($hoy->format('l') == 'Saturday' or $hoy->format('l') == 'Sunday') {
                            $hoy->modify("+1 days");               
                        }else{
                            $variable++;
                            $hoy->modify("+1 days");                       
                        }                   
                    }                
                    if ($variable<=3) {
                        $requerimiento ['status'] = 2;
                        $vencer++;
                    } else {
                        $requerimiento ['status'] = 1;
                        $alDia++;
                    }
                    $variable = 0;
                    unset($hoy);
                    $hoy = new DateTime();                           
                }
                $requerimiento = (object) $requerimiento;
                $requerimientos [] = $requerimiento;
            }                   
        }
        $arrayEquipo = array_unique($arrayEquipo);
        $arrayEquipos = [];
        $requerimientos = (object)$requerimientos;
        
        
        //Cantidad de requerimientos por equipo del solicitante al día, por vencer, vencido
        $porEquipoAldia = [];
        $porEquipoPorVencer = [];
        $porEquipoVencido = [];
        foreach($arrayEquipo as $idEquipo)
        {
            $alDia = 0;
            $vencer = 0;
            $vencido = 0;            
            $equipo = Team::where('id',$idEquipo)->first();
            $arrayEquipos[]=$equipo->nameTeam;
            $req = DB::table('requerimientos_equipos')->where([
                ['rutEmpresa', auth()->user()->rutEmpresa],
                ['estado', 1],
                ['aprobacion',3],
                ['idSolicitante', $solicitante->id],
                ['idEquipo', $idEquipo],
            ])->get();
            foreach($req as $requerimiento)
            {
                    if($requerimiento->fechaCierre == "9999-12-31 00:00:00")
                    {
                        $alDia++;
                    } else 
                    {
                        $hoy = new DateTime();
                        $cierre = new DateTime($requerimiento->fechaCierre);
                        if ($cierre->getTimestamp()<$hoy->getTimestamp()) {
                            $vencido++;
                        } else {
                            $variable = 0;
                            while ($hoy->getTimestamp() < $cierre->getTimestamp())
                            {                                        
                                if ($hoy->format('l') == 'Saturday' or $hoy->format('l') == 'Sunday') {
                                    $hoy->modify("+1 days");               
                                }else
                                {
                                    $variable++;
                                    $hoy->modify("+1 days");                       
                                }                   
                            }                
                            if ($variable<=3) {
                                $vencer++;
                            } else {
                                $alDia++;
                            }
                            $variable = 0;
                            unset($hoy);
                            $hoy = new DateTime();                           
                        }                        
                    }             
            }
            $porEquipoAldia[]=$alDia;
            $porEquipoPorVencer[]=$vencer;
            $porEquipoVencido[]=$vencido;
        }
        
        return view('dashboard.solicitante', compact(''))
    }
}
