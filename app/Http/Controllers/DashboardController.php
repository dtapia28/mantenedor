<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Requerimiento;
use App\Resolutor;
use App\Team;
use DateTime;

class DashboardController extends Controller
{
    public function index()
    {
    	$verde = 0;
    	$amarillo = 0;
    	$rojo = 0;
    	$requerimientos = Requerimiento::where('estado', 1)->get();
    	foreach ($requerimientos as $requerimiento) {
    		$hoy = new DateTime();
    		$fechaCierre = new DateTime($requerimiento->fechaCierre);
    		if ($requerimiento->fechaRealCierre == "" or ($hoy->getTimestamp() < $fechaCierre->getTimestamp())) {
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
    	return view('dashboard.index', compact("verde", "amarillo", "rojo"));
    }

    public function green(){

        $resolutors = Resolutor::all();  
        $teams = Team::all();          	
    	$requerimientos = Requerimiento::where('estado', 1)->get();
    	$requerimientosGreen = [];
    	foreach ($requerimientos as $requerimiento) {
    		$hoy = new DateTime();
    		$fechaCierre = new DateTime($requerimiento->fechaCierre);
    		if ($requerimiento->fechaRealCierre == "" or ($hoy->getTimestamp() < $fechaCierre->getTimestamp())) {
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
    	
    	return view('dashboard.green', compact('requerimientosGreen', 'resolutors', 'teams'));   	    	
    }

    public function yellow(){

        $resolutors = Resolutor::all();  
        $teams = Team::all();           
        $requerimientos = Requerimiento::where('estado', 1)->get();
        $requerimientosYellow = [];
        foreach ($requerimientos as $requerimiento) {
            $hoy = new DateTime();
            $fechaCierre = new DateTime($requerimiento->fechaCierre);
            if ($requerimiento->fechaRealCierre == "" or ($hoy->getTimestamp() < $fechaCierre->getTimestamp())) {
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
        
        return view('dashboard.yellow', compact('requerimientosYellow', 'resolutors', 'teams'));              
    }

    public function red(){

        $resolutors = Resolutor::all();  
        $teams = Team::all();           
        $requerimientos = Requerimiento::where('estado', 1)->get();
        $requerimientosRed = [];
        foreach ($requerimientos as $requerimiento) {
            $hoy = new DateTime();
            $fechaCierre = new DateTime($requerimiento->fechaCierre);
            if ($requerimiento->fechaRealCierre != "" or ($hoy->getTimestamp() > $fechaCierre->getTimestamp())) {
                $requerimientosRed[] = $requerimiento;                 
            }
        }   
        
        return view('dashboard.red', compact('requerimientosRed', 'resolutors', 'teams'));              
    }        
}
