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
    public function index()
    {
        // cantidad de requerimientos al día, por vencer y vencidos.
        $req = Requerimiento::where([
            ['rutEmpresa', auth()->user()->rutEmpresa],
            ['estado', 1],
            ['aprobacion', 3],
        ])->get();
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
        $requerimientos = (object)$requerimientos;
        
        $equipos = Team::where('rutEmpresa',auth()->user()->rutEmpresa)->get();
        $arrayEquipos = [];
        $arrayAlDia = [];
        $arrayPorVencer = [];
        $arrayVencidos = [];
        foreach ($equipos as $equipo)
        {
           $arrayEquipos[] = $equipo->nameTeam;
           $req = DB::table('requerimientos_equipos')->where([
                ['estado', '=', 1],
                ['aprobacion', 3],
                ['rutEmpresa', '=', auth()->user()->rutEmpresa],
                ['idEquipo', $equipo->id],
           ])->get();
           
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
        
        $req = Requerimiento::where([
            ['rutEmpresa', auth()->user()->rutEmpresa],
            ['estado', 2],
        ])->get();
        
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
        
        $req2 = Requerimiento::where([
            ['rutEmpresa', auth()->user()->rutEmpresa],
            ['estado', 1],
            ['aprobacion', 4],
        ])->get();
        
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
        
        
        //Requerimientos cerrados por equipo al día, por vencer y vencidos
        $porEquipoAlDia = [];
        $porEquipoPorVencer = [];
        $porEquipoVencido = [];
        foreach ($equipos as $equipo)
        {
            $varAlDia = 0;
            $varPorVencer = 0;
            $varVencido = 0;            
            $req = DB::table('requerimientos_equipos')->where([
                ['rutEmpresa', auth()->user()->rutEmpresa],
                ['estado', 2],
                ['idEquipo', $equipo->id],
            ])->get();

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
            $req2 = DB::table('requerimientos_equipos')->where([
                ['rutEmpresa', auth()->user()->rutEmpresa],
                ['estado', 1],
                ['aprobacion', 4],
                ['idEquipo', $equipo->id],
            ])->get();
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
        }
        $porEquipoAlDia=(object)$porEquipoAlDia;
        $porEquipoPorVencer=(object)$porEquipoPorVencer;
        $porEquipoVencido=(object)$porEquipoVencido;
        
        
        return view('dashboard.administrador', compact('requerimientos', 'alDia', 'vencer', 'vencido',
                'arrayEquipos', 'arrayAlDia', 'arrayPorVencer', 'arrayVencidos', 'cerradosAlDia',
                'cerradosPorVencer', 'cerradosVencidos', 'porEquipoAlDia', 'porEquipoPorVencer',
                'porEquipoVencido'));
    }
}
