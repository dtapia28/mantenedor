<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Requerimiento;
use App\Resolutor;
use App\Team;
use App\Solicitante;
use DateTime;

class DashboardController extends Controller
{
    public function index(Request $request)
    {   

        $teams = Team::where('rutEmpresa', auth()->user()->rutEmpresa)->get();
        $verde = 0;
        $amarillo = 0;
        $rojo = 0;
    	$requerimientos = Requerimiento::where([
            ['estado', 1],
            ['rutEmpresa', auth()->user()->rutEmpresa],
        ])->get();
    	foreach ($requerimientos as $requerimiento) {
    		$hoy = new DateTime();
    		$fechaCierre = new DateTime($requerimiento->fechaCierre);
    		if ($requerimiento->fechaRealCierre == "" and ($hoy->getTimestamp() < $fechaCierre->getTimestamp())) {
    			$variable = 0;
    			while ($hoy->getTimestamp() < $fechaCierre->getTimestamp()) {

		           if ($hoy->format('l') == 'Saturday' or $hoy->format('l') == 'Sunday') {
		                $hoy->modify("+1 days");               
		            }else{
		                $variable++;
		                $hoy->modify("+1 days");                       
		            }    				
    			}
    		if ($variable >= 3) {
    			$verde++;    			
    		} else {
    			$amarillo++;
    		}    			
    		} else {
    			$rojo++;
    		}

    	}


    	return view('dashboard.index', compact("verde", "amarillo", "rojo", "teams"));
    }

    public function green(){

        $resolutors = Resolutor::where('rutEmpresa', auth()->user()->rutEmpresa)->get();
        $teams = Team::where('rutEmpresa', auth()->user()->rutEmpresa)->get();  
        $solicitantes = Solicitante::where('rutEmpresa', auth()->user()->rutEmpresa)->get();        	
    	$requerimientos = Requerimiento::where([
            ['estado', 1],
            ['rutEmpresa', '=', auth()->user()->rutEmpresa],
        ])->get();
    	$requerimientosGreen = [];
    	foreach ($requerimientos as $requerimiento) {
    		$hoy = new DateTime();
    		$fechaCierre = new DateTime($requerimiento->fechaCierre);
    		if ($requerimiento->fechaRealCierre == "" and ($hoy->getTimestamp() < $fechaCierre->getTimestamp())) {
    			$variable = 0;
    			while ($hoy->getTimestamp() < $fechaCierre->getTimestamp()) {

		           if ($hoy->format('l') == 'Saturday' or $hoy->format('l') == 'Sunday') {
		                $hoy->modify("+1 days");               
		            }else{
		                $variable++;
		                $hoy->modify("+1 days");                       
		            }    				
    			}
    		if ($variable >= 3) {
    			$requerimientosGreen[] = $requerimiento;    			
    		}
    		}

    	}	
    	
    	return view('dashboard.green', compact('requerimientosGreen', 'resolutors', 'teams', 'solicitantes'));   	    	
    }

    public function yellow(){

        $resolutors = Resolutor::all();  
        $teams = Team::all();           
        $requerimientos = Requerimiento::where('estado', 1)->get();
        $solicitantes = Solicitante::where('rutEmpresa', auth()->user()->rutEmpresa)->get();
        $requerimientosYellow = [];
        foreach ($requerimientos as $requerimiento) {
            $hoy = new DateTime();
            $fechaCierre = new DateTime($requerimiento->fechaCierre);
            if ($requerimiento->fechaRealCierre == "" and ($hoy->getTimestamp() < $fechaCierre->getTimestamp())) {
                $variable = 0;
                while ($hoy->getTimestamp() < $fechaCierre->getTimestamp()) {

                   if ($hoy->format('l') == 'Saturday' or $hoy->format('l') == 'Sunday') {
                        $hoy->modify("+1 days");               
                    }else{
                        $variable++;
                        $hoy->modify("+1 days");                       
                    }                   
                }
            if ($variable <= 2 and $variable >=0) {
                $requerimientosYellow[] = $requerimiento;                
            }
            }
        }   
        
        return view('dashboard.yellow', compact('requerimientosYellow', 'resolutors', 'teams', 'solicitantes'));              
    }

    public function red(){

        $resolutors = Resolutor::all();  
        $teams = Team::all();           
        $requerimientos = Requerimiento::where('estado', 1)->get();
        $solicitantes = Solicitante::where('rutEmpresa', auth()->user()->rutEmpresa)->get();
        $requerimientosRed = [];
        foreach ($requerimientos as $requerimiento) {
            $hoy = new DateTime();
            $fechaCierre = new DateTime($requerimiento->fechaCierre);
            if ($requerimiento->fechaRealCierre != "" or ($hoy->getTimestamp() > $fechaCierre->getTimestamp())) {
                $requerimientosRed[] = $requerimiento;                 
            }
        }   
        
        return view('dashboard.red', compact('requerimientosRed', 'resolutors', 'teams', 'solicitantes'));              
    }        
}
