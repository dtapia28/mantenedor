<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Requerimiento;
use App\Solicitante;
use App\Resolutor;
use App\Team;
use DateTime;

class GraficosSolicitanteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request=null)
    {
        $rango_fecha = $request->rango_fecha;
        if ($request == null || $request == "") {
            $desde = date('Y-m-').'01';
            $hasta = date('Y-m-d');
        } else {
            switch ($rango_fecha) {
                case 'mes_actual':
                    $desde = date('Y-m-').'01';
                    $hasta = date('Y-m-d');
                    break;
                case 'mes_ult3':
                    $desde = date("Y-m-d", strtotime("-3 month"));
                    $hasta = date('Y-m-d');
                    break;
                case 'mes_ult6':
                    $desde = date("Y-m-d", strtotime("-6 month"));
                    $hasta = date('Y-m-d');
                    break;
                case 'mes_ult12':
                    $desde = date("Y-m-d", strtotime("-12 month"));
                    $hasta = date('Y-m-d');
                    break;
                case 'por_rango':
                    $desde = substr($request->fec_des, 6, 4).'-'.substr($request->fec_des, 3, 2).'-'.substr($request->fec_des, 0, 2);
                    $hasta = substr($request->fec_has, 6, 4).'-'.substr($request->fec_has, 3, 2).'-'.substr($request->fec_has, 0, 2);
                    break;
                default:
                    $desde = date('Y-m-').'01';
                    $hasta = date('Y-m-d');
                    break;
            }
        }
        //Cantidad de requerimientos de solicitante al día, por vencer, vencido
        $solicitante = Solicitante::where('idUser', auth()->user()->id)->first();
        $user = DB::table('usuarios')->where('idUser', auth()->user()->id)->first();
        $solicitante2 = Solicitante::where('idUser', $user->idUser)->first();   
        $equipo2 = Team::where('id', $resolutor->idTeam)->first();
        $req = DB::table('requerimientos')
                    ->where('rutEmpresa', auth()->user()->rutEmpresa)
                    ->where('estado', 1)
                    ->where('aprobacion', 3)
                    ->where('idSolicitante', $solicitante2->id)
                    ->whereBetween('fechaSolicitud', [$desde, $hasta])
                    ->get();
        
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
        if ($req->all() != "" && $req->all() != null && !is_null($req->all())) 
            $requerimientos = (object)$requerimientos;
        else
            $requerimientos = (object)[];
        
        
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
            $req = DB::table('requerimientos_equipos')
                ->where('rutEmpresa', auth()->user()->rutEmpresa)
                ->where('estado', 1)
                ->where('aprobacion',3)
                ->where('idSolicitante', $solicitante->id)
                ->where('idEquipo', $idEquipo)
                ->whereBetween('fechaSolicitud', [$desde, $hasta])
                ->get();
            
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

        $equipo_id = $equipo2->id;
        $solicitante_id = $solicitante2->id;
        
        $sqlValoresReq = DB::select('select count(*) as cant from requerimientos where idSolicitante = ? AND created_at BETWEEN ? AND ?', [$solicitante_id, $desde, $hasta]);
        $valores['requerimientos'] = $sqlValoresReq[0]->cant;
        $sqlValoresRes = DB::select('select count(*) as cant from resolutors where rutEmpresa = ? AND idTeam = ?', [auth()->user()->rutEmpresa, $equipo_id]);
        $valores['resolutores'] = $sqlValoresRes[0]->cant;
        $sqlValoresSol = DB::select('select count(*) as cant from solicitantes where rutEmpresa = ?', [auth()->user()->rutEmpresa]);
        $valores['solicitantes'] = $sqlValoresSol[0]->cant;
        $sqlValoresEq = DB::select('select count(*) as cant from teams where rutEmpresa = ? AND id = ?', [auth()->user()->rutEmpresa, $equipo_id]);
        $valores['equipos'] = $sqlValoresEq[0]->cant;
        
        return compact('requerimientos', 'arrayEquipos', 'alDia', 'vencer', 'vencido', 'porEquipoAldia', 'porEquipoPorVencer', 'porEquipoVencido', 'rango_fecha', 'desde', 'hasta', 'valores');
    }
}
