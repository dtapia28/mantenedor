<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Requerimiento;
use App\Resolutor;
use App\Team;
use App\Solicitante;
use DateTime;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {   
        $user = DB::table('usuarios')->where('idUser', auth()->user()->id)->get();
        $lider = 0;
        if ($user[0]->nombre == "resolutor") {
            $resolutor = Resolutor::where('idUser', $user[0]->idUser)->first('lider');
            $lider = $resolutor->lider;           
        }
        
        if ($user[0]->nombre == "resolutor")
        {   
            $teams = Team::where('rutEmpresa', auth()->user()->rutEmpresa)->get(['id', 'nameTeam'])->toArray(); 
            $res = Resolutor::where('idUser', $user[0]->idUser)->first();
            $equipo = Team::where('id', $res->idTeam)->first();
            $verde = 0;
            $amarillo = 0;
            $rojo = 0;
            $requerimientos = DB::table('requerimientos_equipos')->where([
                ['estado', 1],
                ['rutEmpresa', auth()->user()->rutEmpresa],
                ['idEquipo', $equipo->id],
            ])->get();
            foreach ($requerimientos as $requerimiento) 
            {
                if ($requerimiento->fechaCierre == "9999-12-31 00:00:00") 
                {
                } else
                {
                    $hoy = new DateTime();
                    $fechaCierre = new DateTime($requerimiento->fechaCierre);
                    if ($requerimiento->fechaRealCierre == "" and ($hoy->getTimestamp() < $fechaCierre->getTimestamp())) {
                        $variable = 0;
                        while ($hoy->getTimestamp() < $fechaCierre->getTimestamp()) {
                            if ($hoy->format('l') == 'Saturday' or $hoy->format('l') == 'Sunday')
                            {
                                $hoy->modify("+1 days");               
                            }
                            else
                            {
                                $variable++;
                                $hoy->modify("+1 days");                       
                            }    				
                        }
                        if ($variable >= 3) {
                            $verde++;    			
                        } else {
                            $amarillo++;
                        }    			
                    } 
                    else 
                    {
                        $rojo++;
                    }
<<<<<<< HEAD
                }    
=======
                }
            }

            if ($everde != 0 or $eamarillo != 0 or $erojo != 0) {

                array_push($equipos2, array('id'=>$equipo['id'], 'nombre'=>$equipo['nameTeam'], 'verde'=>$everde, 'amarillo'=>$eamarillo, 'rojo'=>$erojo, 'conteo' =>$econteo));
                $everde = 0;
                $eamarillo = 0;
                $erojo = 0;
                $econteo = 0;
                
            }
        }
        
        // Verifica si el usuario logueado es un resolutor marcado como líder
        $resolutor = DB::table('resolutors')->where('idUser', auth()->user()->id)->get();
        $resolutor_lider = $resolutor[0]->lider;
        
        // Termino de prueba        

    	return view('dashboard.index', compact("verde", "amarillo", "rojo", "teams", "equipos2", 'user', 'resolutor_lider'));
    }

    public function green(){

        $user = DB::table('usuarios')->where('idUser', auth()->user()->id)->get();
        $resolutors = Resolutor::where('rutEmpresa', auth()->user()->rutEmpresa)->get();
        $teams = Team::where('rutEmpresa', auth()->user()->rutEmpresa)->get();  
        $solicitantes = Solicitante::where('rutEmpresa', auth()->user()->rutEmpresa)->get();        	
    	$requerimientos = Requerimiento::where([
            ['estado', 1],
            ['rutEmpresa', '=', auth()->user()->rutEmpresa],
        ])->get();
    	$requerimientosGreen = [];
    	foreach ($requerimientos as $requerimiento) 
        {
            if ($requerimiento->fechaCierre == "9999-12-31 00:00:00")
            {  
            } else 
            {
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
>>>>>>> frontend
            }
            $equipos2 = [];
            //Borrar la variable teams
            $resolutores = Resolutor::where([
                ['rutEmpresa', auth()->user()->rutEmpresa],
                ['idTeam', $equipo->id]
            ])->get(['id', 'nombreResolutor']);

            $everde = 0;
            $eamarillo = 0;
            $erojo = 0;
            $econteo = 0;

foreach ($resolutores as $resolutor) 
{
    foreach ($requerimientos as $requerimiento) 
    {
        if ($requerimiento->fechaCierre == "9999-12-31 00:00:00") {
            $everde++; 
        } else 
        {  
            if ($requerimiento->resolutor == $resolutor->id) {
                $econteo++;
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
                    $everde++;               
                } else {
                    $eamarillo++;
                }               
            } else {
                $erojo++;
            }                            
        }
    }    
}
}

if ($everde != 0 or $eamarillo != 0 or $erojo != 0) {

    array_push($equipos2, array('id'=>$resolutor['id'], 'nombre'=>$resolutor['nombreResolutor'], 'verde'=>$everde, 'amarillo'=>$eamarillo, 'rojo'=>$erojo, 'conteo' =>$econteo));
    $everde = 0;
    $eamarillo = 0;
    $erojo = 0;
    $econteo = 0;

}
        } else {
            $equipos = [];
            $teams = Team::where('rutEmpresa', auth()->user()->rutEmpresa)->get(['id', 'nameTeam'])->toArray();
            $resolutores = Resolutor::where([
                ['rutEmpresa', auth()->user()->rutEmpresa],
            ])->get();

            $verde = 0;
            $amarillo = 0;
            $rojo = 0;
            $requerimientos = Requerimiento::where([
                ['estado', 1],
                ['rutEmpresa', auth()->user()->rutEmpresa],
            ])->get();
            foreach ($requerimientos as $requerimiento) 
            {
                if ($requerimiento->fechaCierre == "9999-12-31 00:00:00") 
                {
                } else
                {
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
            }

            $equipos2 = [];
            //Prueba para ver array de equipos
            foreach ($teams as $team) {
                $equipos[] = $team;
            }

            $everde = 0;
            $eamarillo = 0;
            $erojo = 0;
            $econteo = 0;

            foreach ($equipos as $equipo) 
            {
                //Busca los resolutores por equipo
                foreach ($resolutores as $resolutor) 
                {
                    if ($resolutor->idTeam == $equipo['id']) {
                        foreach ($requerimientos as $requerimiento) 
                        {
                            if ($requerimiento->fechaCierre == "9999-12-31 00:00:00") {
                            } else 
                            {
                                
                                if ($requerimiento->resolutor == $resolutor->id) {
                                    $econteo++;
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
                                        $everde++;               
                                    } else {
                                        $eamarillo++;
                                    }               
                                    } else {
                                        $erojo++;
                                    }                            
                                }
                            }    
                        }
                    }
                }

                if ($everde != 0 or $eamarillo != 0 or $erojo != 0) {

                    array_push($equipos2, array('id'=>$equipo['id'], 'nombre'=>$equipo['nameTeam'], 'verde'=>$everde, 'amarillo'=>$eamarillo, 'rojo'=>$erojo, 'conteo' =>$econteo));
                    $everde = 0;
                    $eamarillo = 0;
                    $erojo = 0;
                    $econteo = 0;
                    
                }
            }            
        }
    	return view('dashboard.index', compact("verde", "amarillo", "rojo", "teams", "equipos2", 'user', 'lider'));
    }        
}
