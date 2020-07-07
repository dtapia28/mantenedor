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
        //variable array para contener equipos. Linea 18
        $user = DB::table('usuarios')->where('idUser', auth()->user()->id)->get();        
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
        
        // Verifica si el usuario logueado es un resolutor marcado como líder
        $resolutor = DB::table('resolutors')->where('idUser', auth()->user()->id)->get();
        $resolutor_lider = isset($resolutor[0]->lider) ? $resolutor[0]->lider : "";
        
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
            }
    	}	
    	
    	return view('dashboard.green', compact('requerimientosGreen', 'resolutors', 'teams', 'solicitantes', 'user'));   	    	
    }

    public function yellow(){

        $user = DB::table('usuarios')->where('idUser', auth()->user()->id)->get();  
        $resolutors = Resolutor::all();  
        $teams = Team::all();           
        $requerimientos = Requerimiento::where('estado', 1)->get();
        $solicitantes = Solicitante::where('rutEmpresa', auth()->user()->rutEmpresa)->get();
        $requerimientosYellow = [];
        foreach ($requerimientos as $requerimiento) 
        {
            if ($requerimiento->fechaCierre == "9999-12-31 00:00:00")
            {
            }else
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
                if ($variable <= 2 and $variable >=0) {
                    $requerimientosYellow[] = $requerimiento;                
                }
                }
            }    
        }   
        
        return view('dashboard.yellow', compact('requerimientosYellow', 'resolutors', 'teams', 'solicitantes', 'user'));              
    }

    public function red(){

        $user = DB::table('usuarios')->where('idUser', auth()->user()->id)->get();  
        $resolutors = Resolutor::where('rutEmpresa', auth()->user()->rutEmpresa)->get();  
        $teams = Team::where('rutEmpresa', auth()->user()->rutEmpresa)->get();           
        $requerimientos = Requerimiento::where([
            ['estado', 1],
            ['rutEmpresa', auth()->user()->rutEmpresa],
            ])->get();
        $solicitantes = Solicitante::where('rutEmpresa', auth()->user()->rutEmpresa)->get();
        $requerimientosRed = [];
        foreach ($requerimientos as $requerimiento) 
        {
            $hoy = new DateTime();
            $fechaCierre = new DateTime($requerimiento->fechaCierre);
            if ($requerimiento->fechaRealCierre != "" or ($hoy->getTimestamp() > $fechaCierre->getTimestamp())) {
                $requerimientosRed[] = $requerimiento;                 
            }
        }   
        
        return view('dashboard.red', compact('requerimientosRed', 'resolutors', 'teams', 'solicitantes', 'user'));              
    }
    
    public function getReqEquipoByEstado(Request $request) {
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
                    $desde = substr($request->desde, 6, 4).'-'.substr($request->desde, 3, 2).'-'.substr($request->desde, 0, 2);
                    $hasta = substr($request->hasta, 6, 4).'-'.substr($request->hasta, 3, 2).'-'.substr($request->hasta, 0, 2);
                    break;
                default:
                    $desde = date('Y-m-').'01';
                    $hasta = date('Y-m-d');
                    break;
            }
        }

        $sqlReq = DB::select('SELECT a.id, a.id2, a.textoRequerimiento, DATE_FORMAT(a.fechaSolicitud, "%d/%m/%Y") fechaSolicitud, DATE_FORMAT(a.fechaCierre, "%d/%m/%Y") fechaCierreF, a.fechaCierre, b.nombreResolutor, a.porcentajeEjecutado
                            FROM requerimientos a
                            JOIN resolutors b ON a.resolutor=b.id
                            JOIN teams c ON b.idTeam=c.id
                            WHERE c.nameTeam LIKE ? AND a.estado = ? AND a.aprobacion = 3 AND a.fechaSolicitud BETWEEN ? AND ?', [$request->equipo, 1, $desde, $hasta]);
        $req = collect($sqlReq);
        
        $alDia = 0;
        $vencer = 0;
        $vencido = 0;
        $reqVencidos = [];
        $reqVencers = [];
        $reqAlDias = [];
        
        foreach ($req as $requerimiento) 
        {
            if ($requerimiento->fechaCierre == "9999-12-31 00:00:00") {
                $requerimiento->status = 1;
                $alDia++;
                $requerimiento = (object) $requerimiento;
                $requerimientos [] = $requerimiento;
            } else
            {
                $hoy = new DateTime();
                $cierre = new DateTime($requerimiento->fechaCierre);
                if ($cierre->getTimestamp()<$hoy->getTimestamp()) {
                    $requerimiento->status = 3;
                    $vencido++;
                    $reqVencido = (object) $requerimiento;
                    $reqVencidos [] = $reqVencido;
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
                        $requerimiento->status = 2;
                        $vencer++;
                        $reqVencer = (object) $requerimiento;
                        $reqVencers [] = $reqVencer;
                    } else {
                        $requerimiento->status = 1;
                        $alDia++;
                        $reqAlDia = (object) $requerimiento;
                        $reqAlDias [] = $reqAlDia;
                    }
                    $variable = 0;
                    unset($hoy);
                    $hoy = new DateTime();                           
                }
                $requerimiento = (object) $requerimiento;
                $requerimientos [] = $requerimiento;
            }
        }

        switch($request->estado) {
            case 1: $data = $reqAlDias; break;
            case 2: $data = $reqVencers; break;
            case 3: $data = $reqVencidos; break;
            default: break;
        }

        $records = ['respuesta' => true, 'req' => $data];
        return response()->json($records, 200);
    }

    public function getReqSolicitanteByEstado(Request $request) {
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
                    $desde = substr($request->desde, 6, 4).'-'.substr($request->desde, 3, 2).'-'.substr($request->desde, 0, 2);
                    $hasta = substr($request->hasta, 6, 4).'-'.substr($request->hasta, 3, 2).'-'.substr($request->hasta, 0, 2);
                    break;
                default:
                    $desde = date('Y-m-').'01';
                    $hasta = date('Y-m-d');
                    break;
            }
        }

        $sqlReq = DB::select('SELECT a.id, a.id2, a.textoRequerimiento, DATE_FORMAT(a.fechaSolicitud, "%d/%m/%Y") fechaSolicitud, DATE_FORMAT(a.fechaCierre, "%d/%m/%Y") fechaCierreF, a.fechaCierre, b.nombreResolutor, a.porcentajeEjecutado
                            FROM requerimientos a
                            JOIN resolutors b ON a.resolutor=b.id
                            JOIN solicitantes c ON a.idSolicitante=c.id
                            WHERE c.nombreSolicitante LIKE ? AND a.estado = ? AND a.aprobacion = 3 AND a.fechaSolicitud BETWEEN ? AND ?', [$request->solicitante, 1, $desde, $hasta]);
        $req = collect($sqlReq);
        
        $alDia = 0;
        $vencer = 0;
        $vencido = 0;
        $reqVencidos = [];
        $reqVencers = [];
        $reqAlDias = [];
        
        foreach ($req as $requerimiento) 
        {
            if ($requerimiento->fechaCierre == "9999-12-31 00:00:00") {
                $requerimiento->status = 1;
                $alDia++;
                $requerimiento = (object) $requerimiento;
                $requerimientos [] = $requerimiento;
            } else
            {
                $hoy = new DateTime();
                $cierre = new DateTime($requerimiento->fechaCierre);
                if ($cierre->getTimestamp()<$hoy->getTimestamp()) {
                    $requerimiento->status = 3;
                    $vencido++;
                    $reqVencido = (object) $requerimiento;
                    $reqVencidos [] = $reqVencido;
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
                        $requerimiento->status = 2;
                        $vencer++;
                        $reqVencer = (object) $requerimiento;
                        $reqVencers [] = $reqVencer;
                    } else {
                        $requerimiento->status = 1;
                        $alDia++;
                        $reqAlDia = (object) $requerimiento;
                        $reqAlDias [] = $reqAlDia;
                    }
                    $variable = 0;
                    unset($hoy);
                    $hoy = new DateTime();                           
                }
                $requerimiento = (object) $requerimiento;
                $requerimientos [] = $requerimiento;
            }
        }

        switch($request->estado) {
            case 1: $data = $reqAlDias; break;
            case 2: $data = $reqVencers; break;
            case 3: $data = $reqVencidos; break;
            default: break;
        }

        $records = ['respuesta' => true, 'req' => $data];
        return response()->json($records, 200);
    }

    public function getReqResolutorByEstado(Request $request) {
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
                    $desde = substr($request->desde, 6, 4).'-'.substr($request->desde, 3, 2).'-'.substr($request->desde, 0, 2);
                    $hasta = substr($request->hasta, 6, 4).'-'.substr($request->hasta, 3, 2).'-'.substr($request->hasta, 0, 2);
                    break;
                default:
                    $desde = date('Y-m-').'01';
                    $hasta = date('Y-m-d');
                    break;
            }
        }

        $sqlReq = DB::select('SELECT a.id, a.id2, a.textoRequerimiento, DATE_FORMAT(a.fechaSolicitud, "%d/%m/%Y") fechaSolicitud, DATE_FORMAT(a.fechaCierre, "%d/%m/%Y") fechaCierreF, a.fechaCierre, b.nombreResolutor, a.porcentajeEjecutado
                            FROM requerimientos a
                            JOIN resolutors b ON a.resolutor=b.id
                            WHERE b.nombreResolutor LIKE ? AND a.estado = ? AND a.aprobacion = 3 AND a.fechaSolicitud BETWEEN ? AND ?', [$request->resolutor, 1, $desde, $hasta]);
        
        $req = collect($sqlReq);
        
        $alDia = 0;
        $vencer = 0;
        $vencido = 0;
        $reqVencidos = [];
        $reqVencers = [];
        $reqAlDias = [];
        
        foreach ($req as $requerimiento) 
        {
            if ($requerimiento->fechaCierre == "9999-12-31 00:00:00") {
                $requerimiento->status = 1;
                $alDia++;
                $requerimiento = (object) $requerimiento;
                $requerimientos [] = $requerimiento;
            } else
            {
                $hoy = new DateTime();
                $cierre = new DateTime($requerimiento->fechaCierre);
                if ($cierre->getTimestamp()<$hoy->getTimestamp()) {
                    $requerimiento->status = 3;
                    $vencido++;
                    $reqVencido = (object) $requerimiento;
                    $reqVencidos [] = $reqVencido;
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
                        $requerimiento->status = 2;
                        $vencer++;
                        $reqVencer = (object) $requerimiento;
                        $reqVencers [] = $reqVencer;
                    } else {
                        $requerimiento->status = 1;
                        $alDia++;
                        $reqAlDia = (object) $requerimiento;
                        $reqAlDias [] = $reqAlDia;
                    }
                    $variable = 0;
                    unset($hoy);
                    $hoy = new DateTime();                           
                }
                $requerimiento = (object) $requerimiento;
                $requerimientos [] = $requerimiento;
            }
        }

        switch($request->estado) {
            case 1: $data = $reqAlDias; break;
            case 2: $data = $reqVencers; break;
            case 3: $data = $reqVencidos; break;
            default: break;
        }

        $records = ['respuesta' => true, 'req' => $data];
        return response()->json($records, 200);
    }

    public function getReqResolutorGralByEstado(Request $request) {
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
                    $desde = substr($request->desde, 6, 4).'-'.substr($request->desde, 3, 2).'-'.substr($request->desde, 0, 2);
                    $hasta = substr($request->hasta, 6, 4).'-'.substr($request->hasta, 3, 2).'-'.substr($request->hasta, 0, 2);
                    break;
                default:
                    $desde = date('Y-m-').'01';
                    $hasta = date('Y-m-d');
                    break;
            }
        }
        
        $sqlReq = DB::select('SELECT a.id, a.id2, a.textoRequerimiento, DATE_FORMAT(a.fechaSolicitud, "%d/%m/%Y") fechaSolicitud, DATE_FORMAT(a.fechaCierre, "%d/%m/%Y") fechaCierreF, a.fechaCierre, b.nombreResolutor, a.porcentajeEjecutado
                            FROM requerimientos a
                            JOIN resolutors b ON a.resolutor=b.id
                            WHERE b.idUser = ? AND a.estado = ? AND a.aprobacion = 3 AND a.fechaSolicitud BETWEEN ? AND ?', [auth()->user()->id, 1, $desde, $hasta]);
        $req = collect($sqlReq);
        
        $alDia = 0;
        $vencer = 0;
        $vencido = 0;
        $reqVencidos = [];
        $reqVencers = [];
        $reqAlDias = [];
        
        foreach ($req as $requerimiento) 
        {
            if ($requerimiento->fechaCierre == "9999-12-31 00:00:00") {
                $requerimiento->status = 1;
                $alDia++;
                $requerimiento = (object) $requerimiento;
                $requerimientos [] = $requerimiento;
            } else
            {
                $hoy = new DateTime();
                $cierre = new DateTime($requerimiento->fechaCierre);
                if ($cierre->getTimestamp()<$hoy->getTimestamp()) {
                    $requerimiento->status = 3;
                    $vencido++;
                    $reqVencido = (object) $requerimiento;
                    $reqVencidos [] = $reqVencido;
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
                        $requerimiento->status = 2;
                        $vencer++;
                        $reqVencer = (object) $requerimiento;
                        $reqVencers [] = $reqVencer;
                    } else {
                        $requerimiento->status = 1;
                        $alDia++;
                        $reqAlDia = (object) $requerimiento;
                        $reqAlDias [] = $reqAlDia;
                    }
                    $variable = 0;
                    unset($hoy);
                    $hoy = new DateTime();                           
                }
                $requerimiento = (object) $requerimiento;
                $requerimientos [] = $requerimiento;
            }
        }

        switch($request->estado) {
            case 1: $data = $reqAlDias; break;
            case 2: $data = $reqVencers; break;
            case 3: $data = $reqVencidos; break;
            default: break;
        }

        $records = ['respuesta' => true, 'req' => $data];
        return response()->json($records, 200);
    }

    public function getReqSolicitanteGralByEstado(Request $request) {
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
                    $desde = substr($request->desde, 6, 4).'-'.substr($request->desde, 3, 2).'-'.substr($request->desde, 0, 2);
                    $hasta = substr($request->hasta, 6, 4).'-'.substr($request->hasta, 3, 2).'-'.substr($request->hasta, 0, 2);
                    break;
                default:
                    $desde = date('Y-m-').'01';
                    $hasta = date('Y-m-d');
                    break;
            }
        }

        $sqlReq = DB::select('SELECT a.id, a.id2, a.textoRequerimiento, DATE_FORMAT(a.fechaSolicitud, "%d/%m/%Y") fechaSolicitud, DATE_FORMAT(a.fechaCierre, "%d/%m/%Y") fechaCierre, b.nombreResolutor, a.porcentajeEjecutado
                            FROM requerimientos a
                            JOIN resolutors b ON a.resolutor=b.id
                            JOIN solicitantes c ON a.idSolicitante=c.id
                            WHERE c.idUser = ? AND a.aprobacion = 3 AND a.estado = ?', [auth()->user()->id, 1]);

        $req = collect($sqlReq);
                
        $alDia = 0;
        $vencer = 0;
        $vencido = 0;
        $reqVencidos = [];
        $reqVencers = [];
        $reqAlDias = [];

        foreach ($req as $requerimiento) 
        {
            if ($requerimiento->fechaCierre == "9999-12-31 00:00:00") {
                $requerimiento->status = 1;
                $alDia++;
                $requerimiento = (object) $requerimiento;
                $requerimientos [] = $requerimiento;
            } else
            {
                $hoy = new DateTime();
                $cierre = new DateTime($requerimiento->fechaCierre);
                if ($cierre->getTimestamp()<$hoy->getTimestamp()) {
                    $requerimiento->status = 3;
                    $vencido++;
                    $reqVencido = (object) $requerimiento;
                    $reqVencidos [] = $reqVencido;
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
                        $requerimiento->status = 2;
                        $vencer++;
                        $reqVencer = (object) $requerimiento;
                        $reqVencers [] = $reqVencer;
                    } else {
                        $requerimiento->status = 1;
                        $alDia++;
                        $reqAlDia = (object) $requerimiento;
                        $reqAlDias [] = $reqAlDia;
                    }
                    $variable = 0;
                    unset($hoy);
                    $hoy = new DateTime();                           
                }
                $requerimiento = (object) $requerimiento;
                $requerimientos [] = $requerimiento;
            }
        }

        switch($request->estado) {
            case 1: $data = $reqAlDias; break;
            case 2: $data = $reqVencers; break;
            case 3: $data = $reqVencidos; break;
            default: break;
        }
        
        $records = ['respuesta' => true, 'req' => $data];
        return response()->json($records, 200);
    }
}
