<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Requerimiento;
use App\Team;
use DateTime;

class GraficosAdministradorController extends Controller
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
        $req = Requerimiento::where('rutEmpresa', auth()->user()->rutEmpresa)
                            ->where('estado', 1)
                            ->where('aprobacion', 3)
                            ->whereBetween('fechaSolicitud', [$desde, $hasta])
                            ->get();
        $alDia = 0;
        $vencer = 0;
        $vencido = 0;
        foreach ($req as $requerimiento) 
        {
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
        
        $equipos = Team::where('rutEmpresa',auth()->user()->rutEmpresa)->get();
        $arrayEquipos = [];
        $arrayAlDia = [];
        $arrayPorVencer = [];
        $arrayVencidos = [];
        foreach ($equipos as $equipo)
        {
           $arrayEquipos[] = $equipo->nameTeam;
           $req = DB::table('requerimientos_equipos')
                    ->where('estado', '=', 1)
                    ->where('aprobacion', 3)
                    ->where('rutEmpresa', '=', auth()->user()->rutEmpresa)
                    ->where('idEquipo', $equipo->id)
                    ->whereBetween('fechaSolicitud', [$desde, $hasta])
                    ->get();
           
           $EalDia = 0;
           $EporVencer = 0;
           $Evencido = 0;
            
            foreach ($req as $requerimiento)
            {
                $requerimiento = (array)$requerimiento;
                if ($requerimiento['fechaCierre'] == "9999-12-31 00:00:00") {
                    $EalDia++;
                } else
                {
                    $hoy = new DateTime();
                    $cierre = new DateTime($requerimiento['fechaCierre']);
                    if ($cierre->getTimestamp()<$hoy->getTimestamp()) {
                        $Evencido++;
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
                            $EporVencer++;
                        } else {
                            $requerimiento ['status'] = 1;
                            $EalDia++;
                        }
                        $variable = 0;
                        unset($hoy);
                        $hoy = new DateTime();                           
                    }
                }                
            }
            
            $arrayAlDia[] = $EalDia;
            $arrayPorVencer[] = $EporVencer;
            $arrayVencidos[] = $Evencido;
        }
        
        $arrayEquipos=(object)$arrayEquipos;
        $arrayAlDia=(object)$arrayAlDia;
        $arrayPorVencer=(object)$arrayPorVencer;
        $arrayVencidos=(object)$arrayVencidos;
        
        
        
        //Requerimientos cerrados
        $cerradosAlDia = 0;
        $cerradosPorVencer = 0;
        $cerradosVencidos = 0;
        
        $req = Requerimiento::where('rutEmpresa', auth()->user()->rutEmpresa)
                            ->where('estado', 2)
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
                                $cerradosVencidos++;
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
                                    $cerradosAlDia++;
                                } else {
                                    $cerradosPorVencer++;
                                }
                            }                            
                        }
                    } else {
                        $cierre = new DateTime($requerimiento->fechaCierre);
                        $cerrado = new DateTime($requerimiento->fechaLiquidacion);
                        
                        if($cierre->getTimestamp()<$cerrado->getTimestamp()){
                            $cerradosVencidos++;
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
                                $cerradosAlDia++;
                            } else {
                                $cerradosPorVencer++;
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
                                $cerradosVencidos++;
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
                                    $cerradosAlDia++;
                                } else {
                                    $cerradosPorVencer++;
                                }
                            }                            
                        }
                    } else {
                        $cierre = new DateTime($requerimiento->fechaCierre);
                        $cerrado = new DateTime($requerimiento->updated_at);
                        
                        if($cierre->getTimestamp()<$cerrado->getTimestamp()){
                            $cerradosVencidos++;
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
                                $cerradosAlDia++;
                            } else {
                                $cerradosPorVencer++;
                            }
                        }                            
                    }                    
                }
            }
        }
        
        $req2 = Requerimiento::where('rutEmpresa', auth()->user()->rutEmpresa)
                            ->where('estado', 1)
                            ->where('aprobacion', 4)
                            ->whereBetween('fechaSolicitud', [$desde, $hasta])
                            ->get();
        
        if(isset($req2)){
            foreach($req2 as $requerimiento){
                if($requerimiento->fechaLiquidacion != "0000-00-00 00:00:00"){
                    if($requerimiento->fechaRealCierre != null){
                        $cierre = new DateTime($requerimiento->fechaCierre);
                        $cerrado = new DateTime($requerimiento->fechaLiquidacion);
                        $real = new DateTime($requerimiento->fechaRealCierre);
                        if($cierre->getTimestamp()<=$real->getTimestamp()){
                            if($cierre->getTimestamp()<$cerrado->getTimestamp()){
                                $cerradosVencidos++;
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
                                    $cerradosAlDia++;
                                } else {
                                    $cerradosPorVencer++;
                                }
                            }                            
                        }
                    } else {
                        $cierre = new DateTime($requerimiento->fechaCierre);
                        $cerrado = new DateTime($requerimiento->fechaLiquidacion);
                        
                        if($cierre->getTimestamp()<$cerrado->getTimestamp()){
                            $cerradosVencidos++;
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
                                $cerradosAlDia++;
                            } else {
                                $cerradosPorVencer++;
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
                                $cerradosVencidos++;
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
                                    $cerradosAlDia++;
                                } else {
                                    $cerradosPorVencer++;
                                }
                            }                            
                        }
                    } else {
                        $cierre = new DateTime($requerimiento->fechaCierre);
                        $cerrado = new DateTime($requerimiento->updated_at);
                        
                        if($cierre->getTimestamp()<$cerrado->getTimestamp()){
                            $cerradosVencidos++;
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
                                $cerradosAlDia++;
                            } else {
                                $cerradosPorVencer++;
                            }
                        }                            
                    }                    
                }
            }
        }
        
        if(($cerradosAlDia+$cerradosPorVencer+$cerradosVencidos) == 0)
        {
            $divisor = 1;
        } else {
            $divisor = $cerradosAlDia+$cerradosPorVencer+$cerradosVencidos;
        }
        
        $porcentajeAlDia = ((($cerradosPorVencer/2)+$cerradosAlDia)/$divisor)*100;
        
        
        //Requerimientos cerrados por equipo al día, por vencer y vencidos
        $porEquipoAlDia = [];
        $porEquipoPorVencer = [];
        $porEquipoVencido = [];
        foreach ($equipos as $equipo)
        {
            $varAlDia = 0;
            $varPorVencer = 0;
            $varVencido = 0;            
            $req = DB::table('requerimientos_equipos')
                        ->where('rutEmpresa', auth()->user()->rutEmpresa)
                        ->where('estado', 2)
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
                                    $varVencido++;
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
                                        $varAlDia++;
                                    } else {
                                        $varPorVencer++;
                                    }
                                }                            
                            }
                        } else {
                            $cierre = new DateTime($requerimiento->fechaCierre);
                            $cerrado = new DateTime($requerimiento->fechaLiquidacion);

                            if($cierre->getTimestamp()<$cerrado->getTimestamp()){
                                $varVencido++;
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
                                    $varAlDia++;
                                } else {
                                    $varPorVencer++;
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
                                    $varVencido++;
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
                                        $varAlDia++;
                                    } else {
                                        $varPorVencer++;
                                    }
                                }                            
                            }
                        } else {
                            $cierre = new DateTime($requerimiento->fechaCierre);
                            $cerrado = new DateTime($requerimiento->updated_at);

                            if($cierre->getTimestamp()<$cerrado->getTimestamp()){
                                $varVencido++;
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
                                    $varAlDia++;
                                } else {
                                    $varPorVencer++;
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
                                    $varVencido++;
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
                                        $varAlDia++;
                                    } else {
                                        $varPorVencer++;
                                    }
                                }                            
                            }
                        } else {
                            $cierre = new DateTime($requerimiento->fechaCierre);
                            $cerrado = new DateTime($requerimiento->fechaLiquidacion);

                            if($cierre->getTimestamp()<$cerrado->getTimestamp()){
                                $varVencido++;
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
                                    $varAlDia++;
                                } else {
                                    $varPorVencer++;
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
                                    $varVencido++;
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
                                        $varAlDia++;
                                    } else {
                                        $varPorVencer++;
                                    }
                                }                            
                            }
                        } else {
                            $cierre = new DateTime($requerimiento->fechaCierre);
                            $cerrado = new DateTime($requerimiento->updated_at);

                            if($cierre->getTimestamp()<$cerrado->getTimestamp()){
                                $varVencido++;
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
                                    $varAlDia++;
                                } else {
                                    $varPorVencer++;
                                }
                            }                            
                        }                    
                    }
                }
            }
            $porEquipoAlDia[] = $varAlDia;
            $porEquipoPorVencer[] = $varPorVencer;
            $porEquipoVencido[] = $varVencido;
            if(($varAlDia+$varPorVencer+$varVencido) == 0)
            {
                $divisor = 1;
            } else {
                $divisor = $varAlDia+$varPorVencer+$varVencido;
            }
            $porcentajeEquipoAlDia[] = ((($varPorVencer/2)+$varAlDia)/$divisor)*100;
        }
        $porcentajeEquipoAlDia = (object)$porcentajeEquipoAlDia;
        $porEquipoAlDia=(object)$porEquipoAlDia;
        $porEquipoPorVencer=(object)$porEquipoPorVencer;
        $porEquipoVencido=(object)$porEquipoVencido;

        $sqlValoresReq = DB::select('select count(*) as cant from requerimientos where created_at BETWEEN ? AND ?', [$desde, $hasta]);
        $valores['requerimientos'] = $sqlValoresReq[0]->cant;
        $sqlValoresRes = DB::select('select count(*) as cant from resolutors');
        $valores['resolutores'] = $sqlValoresRes[0]->cant;
        $sqlValoresSol = DB::select('select count(*) as cant from solicitantes');
        $valores['solicitantes'] = $sqlValoresSol[0]->cant;
        $sqlValoresEq = DB::select('select count(*) as cant from teams');
        $valores['equipos'] = $sqlValoresEq[0]->cant;

        return compact('requerimientos', 'alDia', 'vencer', 'vencido',
                'arrayEquipos', 'arrayAlDia', 'arrayPorVencer', 'arrayVencidos', 'cerradosAlDia',
                'cerradosPorVencer', 'cerradosVencidos', 'porEquipoAlDia', 'porEquipoPorVencer',
                'porEquipoVencido', 'porcentajeEquipoAlDia', 'porcentajeAlDia', 'rango_fecha', 'desde', 'hasta', 'valores');
    }
}
