<?php

namespace App\Http\Controllers;

use App\Resolutor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use DateTime;

class IndicadoresController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = DB::table('usuarios')->where('idUser', auth()->user()->id)->get();

        //Requerimientos de los resolutores al día, por vencer y vencido
        $porResolutorAlDia = [];
        $porResolutorPorVencer = [];
        $porResolutorVencido = [];
        
        $resolutores = Resolutor::where([
            ['rutEmpresa', auth()->user()->rutEmpresa],
            ])->get();

            $desde = date('Y-m-').'01';
            $nuevoHasta = strtotime('-1 day', strtotime($desde));
            $hasta = date('Y-m-d', $nuevoHasta);
            $nuevoDesde = strtotime('-1 month', strtotime($desde));
            $desde = date('Y-m-d', $nuevoDesde);
            
            $mes = date("m", strtotime($desde));
            
            switch ($mes){
                case "01":
                    $mes_texto = "enero";
                    break;
                case "02":
                    $mes_texto = "febrero";
                    break;
                case "03":
                    $mes_texto = "marzo";
                    break;
                case "04":
                    $mes_texto = "abril";
                    break;
                case "05":
                    $mes_texto = "mayo";
                    break;
                case "06":
                    $mes_texto = "junio";
                    break;
                case "07":
                    $mes_texto = "julio";
                    break;
                case "08":
                    $mes_texto = "agosto";
                    break;
                case "09":
                    $mes_texto = "septiembre";
                    break;
                case "10":
                    $mes_texto = "octubre";
                    break;
                case "11":
                    $mes_texto = "noviembre";
                    break;
                case "12":
                    $mes_texto = "diciembre";
                    break;
            }
            

        $req = DB::table('requerimientos_equipos')
                    ->where('estado', 2)
                    ->where('aprobacion', 1)
                    ->where('rutEmpresa', auth()->user()->rutEmpresa)
                    ->whereBetween('fechaLiquidacion', [$desde, $hasta])
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
                        if($requerimiento->fechaLiquidacion != null){
                            $hoy = new DateTime($requerimiento->fechaLiquidacion);
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
                        } else {
                            $hoy = new DateTime($requerimiento->updated_at);
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
            }
            $nombreResolutor[]       = $resolutor->nombreResolutor;
            $porResolutorAlDia[]     = $alDia;
            $porResolutorPorVencer[] = $vencer;
            $porResolutorVencido[]   = $vencido;
        }
        
        $value = max($porResolutorAlDia);
        $key = array_search($value, $porResolutorAlDia);
        $destacadoNombre = $nombreResolutor[$key];
        $destacadoAlDia = $value;
        
        // cantidad de requerimientos al día, por vencer y vencidos.
            $desde = date('Y-m-').'01';
            $nuevoHasta = strtotime('-1 day', strtotime($desde));
            $hasta = date('Y-m-d', $nuevoHasta);
            $nuevoDesde = strtotime('-1 month', strtotime($desde));
            $desde = date('Y-m-d', $nuevoDesde); 
        
        $req = DB::table('requerimientos_equipos')
                    ->where('estado', 2)
                    ->where('rutEmpresa', auth()->user()->rutEmpresa)
                    ->whereBetween('fechaLiquidacion', [$desde, $hasta])
                    ->get();
        $alDia = 0;
        $vencer = 0;
        $vencido = 0;
        if(isset($req)){
            foreach ($req as $requerimiento){
                if($requerimiento->fechaLiquidacion != null){
                    if($requerimiento->fechaRealCierre != null){
                        $cierre = new DateTime($requerimiento->fechaCierre);
                        $cerrado = new DateTime($requerimiento->fechaLiquidacion);
                        $real = new DateTime($requerimiento->fechaRealCierre);
                        if($cierre->getTimestamp()<=$real->getTimestamp()){
                            if($cierre->getTimestamp()<$cerrado->getTimestamp()){
                                $vencido++;
                            } else {
                                if($requerimiento->fechaCierre == "9999-12-31 00:00:00")
                                {
                                    $alDia++;
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
                                        $alDia++;
                                    } else {
                                        $vencer++;
                                    }                                    
                                }
                            }                            
                        } else
                        {
                            if($real->getTimestamp()<$cerrado->getTimestamp())
                            {
                                $vencido++;
                            } else
                            {
                                if($requerimiento->fechaRealCierre == "9999-12-31 00:00:00")
                                {
                                    $alDia++;
                                } else
                                {
                                    $variable=0;
                                    while ($cerrado->getTimestamp()<$real->getTimestamp()){
                                        if ($cerrado->format('l') == 'Saturday' or $cerrado->format('l') == 'Sunday') {
                                            $cerrado->modify("+1 days");               
                                        }else{
                                            $variable++;
                                            $cerrado->modify("+1 days");                       
                                        }                                
                                    }
                                    if($variable>3){
                                        $alDia++;
                                    } else {
                                        $vencer++;
                                    }                                    
                                }
                            }                            
                        }
                    } else {
                        $cierre = new DateTime($requerimiento->fechaCierre);
                        $cerrado = new DateTime($requerimiento->fechaLiquidacion);
                        if($cierre->getTimestamp()<$cerrado->getTimestamp()){
                            $vencido++;
                        } else {
                            if($requerimiento->fechaCierre == "9999-12-31 00:00:00")
                            {
                                $alDia++;
                            } else
                            {
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
                                    $alDia++;
                                } else {
                                    $vencer++;
                                }                                
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
                                $vencido++;
                            } else {
                                if($requerimiento->fechaCierre == "9999-12-31 00:00:00")
                                {
                                    $alDia++;
                                } else
                                {
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
                                        $alDia++;
                                    } else {
                                        $vencer++;
                                    }                                    
                                }
                            }                            
                        } else
                        {
                            if($real->getTimestamp()<$cerrado->getTimestamp())
                            {
                                $vencido++;
                            } else
                            {
                                if($requerimiento->fechaRealCierre == "9999-12-31 00:00:00")
                                {
                                    $alDia++;
                                } else
                                {
                                    $variable=0;
                                    while ($cerrado->getTimestamp()<$real->getTimestamp()){
                                        if ($cerrado->format('l') == 'Saturday' or $cerrado->format('l') == 'Sunday') {
                                            $cerrado->modify("+1 days");               
                                        }else{
                                            $variable++;
                                            $cerrado->modify("+1 days");                       
                                        }                                
                                    }
                                    if($variable>3){
                                        $alDia++;
                                    } else {
                                        $vencer++;
                                    }                                    
                                }
                            }                            
                        }
                    } else {
                        $cierre = new DateTime($requerimiento->fechaCierre);
                        $cerrado = new DateTime($requerimiento->updated_at);
                        if($cierre->getTimestamp()<$cerrado->getTimestamp()){
                            $vencido++;
                        } else {
                            if($requerimiento->fechaCierre == "9999-12-31 00:00:00")
                            {
                                $alDia++;
                            } else
                            {
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
                                    $alDia++;
                                } else {
                                    $vencer++;
                                }                                
                            }
                        }                            
                    }                    
                }
            }
        }
        
        return view('Indicadores.index', compact('user', 'destacadoNombre', 'destacadoAlDia',
                'alDia', 'vencer', 'vencido', 'mes_texto'));
    }
}
