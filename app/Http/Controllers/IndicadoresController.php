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

        $req = DB::table('requerimientos_equipos')
                    ->where('estado', 1)
                    ->where('aprobacion', 3)
                    ->where('rutEmpresa', auth()->user()->rutEmpresa)
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
        $req = DB::table('requerimientos_equipos')
                    ->where('estado', 1)
                    ->where('aprobacion', 3)
                    ->where('rutEmpresa', auth()->user()->rutEmpresa)
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
        
        return view('Indicadores.index', compact('user', 'destacadoNombre', 'destacadoAlDia', 'alDia', 'vencer', 'vencido'));
    }
}
