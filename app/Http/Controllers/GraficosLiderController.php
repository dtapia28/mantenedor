<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Team;
use App\Resolutor;
use App\Solicitante;
use DateTime;

class GraficosLiderController extends Controller
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
        // cantidad de requerimientos al día, por vencer y vencidos.
        $user = DB::table('usuarios')->where('idUser', auth()->user()->id)->first();
        $res = Resolutor::where('idUser', $user->idUser)->first();
        $equipo = Team::where('id', $res->idTeam)->first();
        $req = DB::table('requerimientos_equipos')
                    ->where('estado', 1)
                    ->where('aprobacion', 3)
                    ->where('rutEmpresa', auth()->user()->rutEmpresa)
                    ->where('idEquipo', $equipo->id)
                    ->whereBetween('fechaSolicitud', [$desde, $hasta])
                    ->get();
        $alDia = 0;
        $vencer = 0;
        $vencido = 0;
        foreach ($req as $requerimiento) 
        {
            $requerimiento = (array)$requerimiento;
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
        if ($req->all() != "" && $req->all() != null && !is_null($req->all())) 
            $requerimientos = (object)$requerimientos;
        else
            $requerimientos = (object)[];
        
        
        //Requerimientos por solicitante al día, por vencer, vencido.
        $arraySolicitantes = [];
        $solicitantes = Solicitante::where('rutEmpresa', auth()->user()->rutEmpresa)->get();
        foreach ($req as $requerimiento)
        {
            foreach ($solicitantes as $solicitante)
            {
                if($requerimiento->idSolicitante == $solicitante->id)
                {
                    $arraySolicitantes[] = $solicitante->nombreSolicitante;
                }
            }
        }
        $arraySolicitantes = array_unique($arraySolicitantes);
        $porSolicitanteAldia = [];
        $porSolicitantePorVencer = [];
        $porSolicitanteVencido = [];
        foreach ($arraySolicitantes as $solicitante)
        {
            $alDia = 0;
            $vencer = 0;
            $vencido = 0;
            $sol = Solicitante::where('nombreSolicitante', $solicitante)->first();
            foreach ($req as $requerimiento){
                if($requerimiento->idSolicitante == $sol->id)
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
            }
            $porSolicitanteAldia[]=$alDia;
            $porSolicitantePorVencer[]=$vencer;
            $porSolicitanteVencido[]=$vencido;
        }
        
        
        
        //Requerimientos cerrados por el equipo
        $cerradoAlDia = 0;
        $cerradoPorVencer = 0;
        $cerradoVencido = 0;         
        $req = DB::table('requerimientos_equipos')
                    ->where('estado', 2)
                    ->where('rutEmpresa', auth()->user()->rutEmpresa)
                    ->where('idEquipo', $equipo->id)
                    ->whereBetween('fechaSolicitud', [$desde, $hasta])
                    ->get();

        if(isset($req)){
            foreach ($req as $requerimiento){
                if($requerimiento->fechaLiquidacion != "0000-00-00 00:00:00"){
                    if($requerimiento->fechaRealCierre != null){
                        $cierre = new DateTime($requerimiento->fechaCierre);
                        $cerrado = new DateTime($requerimiento->fechaLiquidacion);
                        $real = new DateTime($requerimiento->fechaRealCierre);
                        if($cierre->getTimestamp()<=$real->getTimestamp()){
                            if($cierre->getTimestamp()<$cerrado->getTimestamp()){
                                $cerradoVencido++;
                            } else {
                                $variable=0;
                                while ($cerrado->getTimestamp()<$cierre->getTimestamp()){
                                    if ($cerrado->format('l') == 'Saturday' or $cerrado->format('l') == 'Sunday') {
                                        $cerrado->modify("+1 days");               
                                    }else{
                                        $variable++;
                                        $cerrado->modify("+1 days");                       
                                    }                                
                                }
                                if($variable>3){
                                    $cerradoAlDia++;
                                } else {
                                    $cerradoPorVencer++;
                                }
                            }                            
                        }
                    } else {
                        $cierre = new DateTime($requerimiento->fechaCierre);
                        $cerrado = new DateTime($requerimiento->fechaLiquidacion);
                        if($cierre->getTimestamp()<$cerrado->getTimestamp()){
                            $cerradoVencido++;
                        } else {
                            $variable=0;
                            while ($cerrado->getTimestamp()<$cierre->getTimestamp()){
                                if ($cerrado->format('l') == 'Saturday' or $cerrado->format('l') == 'Sunday') {
                                    $cerrado->modify("+1 days");               
                                }else{
                                    $variable++;
                                    $cerrado->modify("+1 days");                       
                                }                                
                            }
                            if($variable>3){
                                $cerradoAlDia++;
                            } else {
                                $cerradoPorVencer++;
                            }
                        }                            
                    }
                } else {
                    if($requerimiento->fechaRealCierre != null){
                        $cierre = new DateTime($requerimiento->fechaCierre);
                        $cerrado = new DateTime($requerimiento->updated_at);
                        $real = new DateTime($requerimiento->fechaRealCierre);
                        if($cierre->getTimestamp()<=$real->getTimestamp()){
                            if($cierre->getTimestamp()<$cerrado->getTimestamp()){
                                $cerradoVencido++;
                            } else {
                                $variable=0;
                                while ($cerrado->getTimestamp()<$cierre->getTimestamp()){
                                    if ($cerrado->format('l') == 'Saturday' or $cerrado->format('l') == 'Sunday') {
                                        $cerrado->modify("+1 days");               
                                    }else{
                                        $variable++;
                                        $cerrado->modify("+1 days");                       
                                    }                                
                                }
                                if($variable>3){
                                    $cerradoAlDia++;
                                } else {
                                    $cerradoPorVencer++;
                                }
                            }                            
                        }
                    } else {
                        $cierre = new DateTime($requerimiento->fechaCierre);
                        $cerrado = new DateTime($requerimiento->updated_at);
                        if($cierre->getTimestamp()<$cerrado->getTimestamp()){
                            $cerradoVencido++;
                        } else {
                            $variable=0;
                            while ($cerrado->getTimestamp()<$cierre->getTimestamp()){
                                if ($cerrado->format('l') == 'Saturday' or $cerrado->format('l') == 'Sunday') {
                                    $cerrado->modify("+1 days");               
                                }else{
                                    $variable++;
                                    $cerrado->modify("+1 days");                       
                                }                                
                            }
                            if($variable>3){
                                $cerradoAlDia++;
                            } else {
                                $cerradoPorVencer++;
                            }
                        }                            
                    }                    
                }
            }
        }
        $req2 = DB::table('requerimientos_equipos')
                    ->where('rutEmpresa', auth()->user()->rutEmpresa)
                    ->where('estado', 1)
                    ->where('aprobacion', 4)
                    ->where('idEquipo', $equipo->id)
                    ->whereBetween('fechaSolicitud', [$desde, $hasta])
                    ->get();
        
        if(isset($req2)){
            foreach ($req2 as $requerimiento){
                if($requerimiento->fechaLiquidacion != "0000-00-00 00:00:00"){
                    if($requerimiento->fechaRealCierre != null){
                        $cierre = new DateTime($requerimiento->fechaCierre);
                        $cerrado = new DateTime($requerimiento->fechaLiquidacion);
                        $real = new DateTime($requerimiento->fechaRealCierre);
                        if($cierre->getTimestamp()<=$real->getTimestamp()){
                            if($cierre->getTimestamp()<$cerrado->getTimestamp()){
                                $cerradoVencido++;
                            } else {
                                $variable=0;
                                while ($cerrado->getTimestamp()<$cierre->getTimestamp()){
                                    if ($cerrado->format('l') == 'Saturday' or $cerrado->format('l') == 'Sunday') {
                                        $cerrado->modify("+1 days");               
                                    }else{
                                        $variable++;
                                        $cerrado->modify("+1 days");                       
                                    }                                
                                }
                                if($variable>3){
                                    $cerradoAlDia++;
                                } else {
                                    $cerradoPorVencer++;
                                }
                            }                            
                        }
                    } else {
                        $cierre = new DateTime($requerimiento->fechaCierre);
                        $cerrado = new DateTime($requerimiento->fechaLiquidacion);
                        if($cierre->getTimestamp()<$cerrado->getTimestamp()){
                            $cerradoVencido++;
                        } else {
                            $variable=0;
                            while ($cerrado->getTimestamp()<$cierre->getTimestamp()){
                                if ($cerrado->format('l') == 'Saturday' or $cerrado->format('l') == 'Sunday') {
                                    $cerrado->modify("+1 days");               
                                }else{
                                    $variable++;
                                    $cerrado->modify("+1 days");                       
                                }                                
                            }
                            if($variable>3){
                                $cerradoAlDia++;
                            } else {
                                $cerradoPorVencer++;
                            }
                        }                            
                    }
                } else {
                    if($requerimiento->fechaRealCierre != null){
                        $cierre = new DateTime($requerimiento->fechaCierre);
                        $cerrado = new DateTime($requerimiento->updated_at);
                        $real = new DateTime($requerimiento->fechaRealCierre);
                        if($cierre->getTimestamp()<=$real->getTimestamp()){
                            if($cierre->getTimestamp()<$cerrado->getTimestamp()){
                                $cerradoVencido++;
                            } else {
                                $variable=0;
                                while ($cerrado->getTimestamp()<$cierre->getTimestamp()){
                                    if ($cerrado->format('l') == 'Saturday' or $cerrado->format('l') == 'Sunday') {
                                        $cerrado->modify("+1 days");               
                                    }else{
                                        $variable++;
                                        $cerrado->modify("+1 days");                       
                                    }                                
                                }
                                if($variable>3){
                                    $cerradoAlDia++;
                                } else {
                                    $cerradoPorVencer++;
                                }
                            }                            
                        }
                    } else {
                        $cierre = new DateTime($requerimiento->fechaCierre);
                        $cerrado = new DateTime($requerimiento->updated_at);
                        if($cierre->getTimestamp()<$cerrado->getTimestamp()){
                            $cerradoVencido++;
                        } else {
                            $variable=0;
                            while ($cerrado->getTimestamp()<$cierre->getTimestamp()){
                                if ($cerrado->format('l') == 'Saturday' or $cerrado->format('l') == 'Sunday') {
                                    $cerrado->modify("+1 days");               
                                }else{
                                    $variable++;
                                    $cerrado->modify("+1 days");                       
                                }                                
                            }
                            if($variable>3){
                                $cerradoAlDia++;
                            } else {
                                $cerradoPorVencer++;
                            }
                        }                            
                    }                    
                }
            }
        }
        
        
        //Requerimientos por resolutor del equipo al día, por vencer y vencido
        
        $porResolutorAlDia = [];
        $porResolutorPorVencer = [];
        $porResolutorVencido = [];
        $resolutores = Resolutor::where([
            ['rutEmpresa', auth()->user()->rutEmpresa],
            ['idTeam', $equipo->id],
        ])->get();
        
        $arrayResolutores = [];
        
        foreach ($resolutores as $resolutor)
        {
            $arrayResolutores[]=$resolutor->nombreResolutor;
        }

        $req = DB::table('requerimientos_equipos')
                    ->where('estado', 1)
                    ->where('aprobacion', 3)
                    ->where('rutEmpresa', auth()->user()->rutEmpresa)
                    ->where('idEquipo', $equipo->id)
                    ->whereBetween('fechaSolicitud', [$desde, $hasta])
                    ->get();
        
        foreach ($resolutores as $resolutor)
        {
            $alDia = 0;
            $vencer = 0;
            $vencido = 0;            
            foreach ($req as $requerimiento)
            {
                if($requerimiento->resolutor == $resolutor->id)
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
            }
          $porResolutorAlDia[]= $alDia;
          $porResolutorPorVencer[]=$vencer;
          $porResolutorVencido[]=$vencido;
        }

        $sqlAbiertos = DB::select('SELECT COUNT(*) abiertos FROM requerimientos WHERE resolutor = ? AND estado=? AND fechaSolicitud BETWEEN ? AND ?', [$res->id, 1, $desde, $hasta]);
        $sqlCerrados = DB::select('SELECT COUNT(*) cerrados FROM requerimientos WHERE resolutor = ? AND estado=? AND fechaSolicitud BETWEEN ? AND ?', [$res->id, 2, $desde, $hasta]);

        $abiertos = $sqlAbiertos[0]->abiertos;
        $cerrados = $sqlCerrados[0]->cerrados;
        $equipo_id = $equipo->id;
        
        $sqlEq = DB::select("SELECT a.idTeam, b.nameTeam
                            FROM resolutors a
                            JOIN teams b ON a.idTeam=b.id
                            WHERE a.idUser = ?", [auth()->user()->id]);
        $equipo = $sqlEq[0]->nameTeam;

        $sqlValoresReq = DB::select('select count(*) as cant from requerimientos where idEquipo = ? AND created_at BETWEEN ? AND ?', [$equipo_id, $desde, $hasta]);
        $valores['requerimientos'] = $sqlValoresReq[0]->cant;
        $sqlValoresRes = DB::select('select count(*) as cant from resolutors where rutEmpresa = ?', [auth()->user()->rutEmpresa]);
        $valores['resolutores'] = $sqlValoresRes[0]->cant;
        $sqlValoresSol = DB::select('select count(*) as cant from solicitantes where rutEmpresa = ?', [auth()->user()->rutEmpresa]);
        $valores['solicitantes'] = $sqlValoresSol[0]->cant;
        $sqlValoresEq = DB::select('select count(*) as cant from teams where rutEmpresa = ? AND id = ?', [auth()->user()->rutEmpresa, $equipo_id]);
        $valores['equipos'] = $sqlValoresEq[0]->cant;

        return compact('requerimientos', 'alDia', 'vencer', 'vencido',
                'arraySolicitantes', 'porSolicitanteAldia', 'porSolicitantePorVencer',
                'porSolicitanteVencido', 'cerradoAlDia', 'cerradoPorVencer', 'cerradoVencido',
                'arrayResolutores', 'porResolutorAlDia', 'porResolutorPorVencer', 'porResolutorVencido', 'rango_fecha', 'desde', 'hasta', 'abiertos', 'cerrados', 'equipo', 'valores');
    }
}
