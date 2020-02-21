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
    public function index()
    {
        // cantidad de requerimientos al día, por vencer y vencidos.
        $user = DB::table('usuarios')->where('idUser', auth()->user()->id)->first();
        $res = Resolutor::where('idUser', $user->idUser)->first();
        $equipo = Team::where('id', $res->idTeam)->first();
        $req = DB::table('requerimientos_equipos')->where([
            ['estado', 1],
            ['aprobacion', 3],
            ['rutEmpresa', auth()->user()->rutEmpresa],
            ['idEquipo', $equipo->id],
        ])->get();
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
        $requerimientos = (object)$requerimientos;
        
        
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
            $solAlDia = 0;
            $solVencer = 0;
            $solVencido = 0;
            
            $sol = Solicitante::where('nombreSolicitante', $solicitante)->first();
            foreach ($req as $requerimiento){
                if($requerimiento->idSolicitante == $sol->id)
                {
                    if($requerimiento->fechaCierre == "9999-12-31 00:00:00")
                    {
                        $solAlDia++;

                    } else 
                    {
                        $hoy = new DateTime();
                        $cierre = new DateTime($requerimiento->fechaCierre);
                        if ($cierre->getTimestamp()<$hoy->getTimestamp()) {
                            $solVencido++;
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
                                $solVencer++;
                            } else {
                                $solAlDia++;
                            }
                            $variable = 0;
                            unset($hoy);
                            $hoy = new DateTime();                           
                        }                        
                    }
                }
            }
            $porSolicitanteAldia[]=$solAlDia;
            $porSolicitantePorVencer[]=$solVencer;
            $porSolicitanteVencido[]=$solVencido;
        }
        
        
        
        //Requerimientos cerrados por el equipo
        $cerradoAlDia = 0;
        $cerradoPorVencer = 0;
        $cerradoVencido = 0;         
        $req = DB::table('requerimientos_equipos')->where([
            ['estado', 2],
            ['rutEmpresa', auth()->user()->rutEmpresa],
            ['idEquipo', $equipo->id],
        ])->get();

        if(isset($req)){
            foreach ($req as $requerimiento){
                $requerimiento = (array)$requerimiento;
                if($requerimiento['fechaLiquidacion'] != null){
                    if($requerimiento['fechaRealCierre'] != null){
                        $cierre = new DateTime($requerimiento-['fechaCierre']);
                        if($requerimiento['fechaCierre'] == "9999-12-31 00:00:00")
                        {
                            $cerradoAlDia++;
                        } else
                        {
                            $cerrado = new DateTime($requerimiento['fechaLiquidacion']);
                            $real = new DateTime($requerimiento['fechaRealCierre']);
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
                        }
                    } else {
                        $cierre = new DateTime($requerimiento['fechaCierre']);
                        if($requerimiento['fechaCierre'] == "9999-12-31 00:00:00")
                        {
                            $cerradoAlDia++;
                        } else
                        {
                            $cerrado = new DateTime($requerimiento['fechaLiquidacion']);
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
                } else {
                    if($requerimiento['fechaRealCierre'] != null){
                        $cierre = new DateTime($requerimiento['fechaCierre']);
                        if($requerimiento['fechaCierre'] == "9999-12-31 00:00:00")
                        {
                            $cerradoAlDia++;
                        } else
                        {
                            $cerrado = new DateTime($requerimiento['updated_at']);
                            $real = new DateTime($requerimiento['fechaRealCierre']);
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
                        }                        
                    } else {
                        $cierre = new DateTime($requerimiento['fechaCierre']);
                        if($requerimiento['fechaCierre'] == "9999-12-31 00:00:00")
                        {
                            $cerradoAlDia++;
                        } else
                        {
                            $cerrado = new DateTime($requerimiento['updated_at']);
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
        }
        $req2 = DB::table('requerimientos_equipos')->where([
            ['rutEmpresa', auth()->user()->rutEmpresa],
            ['estado', 1],
            ['aprobacion', 4],
            ['idEquipo', $equipo->id],
        ])->get();
        
        if(isset($req2)){
            foreach ($req2 as $requerimiento){
                $requerimiento = (array)$requerimiento;
                if($requerimiento['fechaLiquidacion'] != null){
                    if($requerimiento['fechaRealCierre'] != null){
                        $cierre = new DateTime($requerimiento['fechaCierre']);
                        if($requerimiento['fechaCierre'] == "9999-12-31 00:00:00")
                        {
                            $cerradoAlDia++;
                        } else
                        {
                            $cerrado = new DateTime($requerimiento['fechaLiquidacion']);
                            $real = new DateTime($requerimiento['fechaRealCierre']);
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
                        }                        
                    } else {
                        $cierre = new DateTime($requerimiento['fechaCierre']);
                        if($requerimiento['fechaCierre'] == "9999-12-31 00:00:00")
                        {
                            $cerradoAlDia++;
                        } else
                        {
                            $cerrado = new DateTime($requerimiento['fechaLiquidacion']);
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
                } else {
                    if($requerimiento['fechaRealCierre'] != null){
                        $cierre = new DateTime($requerimiento['fechaCierre']);
                        if($requerimiento['fechaCierre'] == "9999-12-31 00:00:00")
                        {
                            $cerradoAlDia++;
                        } else
                        {
                            $cerrado = new DateTime($requerimiento['updated_at']);
                            $real = new DateTime($requerimiento['fechaRealCierre']);
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
                        }
                    } else {
                        $cierre = new DateTime($requerimiento['fechaCierre']);
                        if($requerimiento['fechaCierre'] == "9999-12-31 00:00:00")
                        {
                            $cerradoAlDia++;
                        } else
                        {
                            $cerrado = new DateTime($requerimiento['updated_at']);
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

        $req = DB::table('requerimientos_equipos')->where([
            ['estado', 1],
            ['aprobacion', 3],
            ['rutEmpresa', auth()->user()->rutEmpresa],
            ['idEquipo', $equipo->id],
        ])->get();        
        
        foreach ($resolutores as $resolutor)
        {
            $resAlDia = 0;
            $resVencer = 0;
            $resVencido = 0;            
            foreach ($req as $requerimiento)
            {
                if($requerimiento->resolutor == $resolutor->id)
                {
                    if($requerimiento->fechaCierre == "9999-12-31 00:00:00")
                    {
                        $resAlDia++;
                    } else 
                    {
                        $hoy = new DateTime();
                        $cierre = new DateTime($requerimiento->fechaCierre);
                        if ($cierre->getTimestamp()<$hoy->getTimestamp()) {
                            $resVencido++;
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
                                $resVencer++;
                            } else {
                                $resAlDia++;
                            }
                            $variable = 0;
                            unset($hoy);
                            $hoy = new DateTime();                           
                        }                        
                    }                    
                }
            }
          $porResolutorAlDia[]= $resAlDia;
          $porResolutorPorVencer[]=$resVencer;
          $porResolutorVencido[]=$resVencido;
        }
        
        $divisor = $cerradoAlDia+$cerradoPorVencer+$cerradoVencido;
        $porcentajeAlDia = ((($cerradoPorVencer/2)+$cerradoAlDia)/$divisor)*100;
        return compact('requerimientos', 'alDia', 'vencer', 'vencido',
                'arraySolicitantes', 'porSolicitanteAldia', 'porSolicitantePorVencer',
                'porSolicitanteVencido', 'cerradoAlDia', 'cerradoPorVencer', 'cerradoVencido',
                'arrayResolutores', 'porResolutorAlDia', 'porResolutorPorVencer',
                'porResolutorVencido', 'porcentajeAlDia');
    }
}
