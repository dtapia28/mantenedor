<?php

namespace App\Http\Controllers;

use App\Requerimiento;
use App\Resolutor;
use App\Priority;
use App\Solicitante;
use App\Avance;
use App\Team;
use App\Anidado;
use App\User;
use App\LogRequerimientos;
use App\Tarea;
use App\Parametros;
use App\Notifications\NewReqResolutor;
use App\Notifications\EnvioWhatsapp;
use App\Notifications\FinalizadoNotifi;
use App\Notifications\RechazadoNotifi;
use App\Notifications\Mail_info;
use Illuminate\Support\Facades\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use DateTime;
use DateInterval;
use App\Http\Controllers\Controller;
use Carbon\Carbon;


class RequerimientoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $state = $request->state;
        $anidados = Anidado::all();

        $solicitantes = Solicitante::where([
            ['rutEmpresa', auth()->user()->rutEmpresa],
        ])->orderBy('nombreSolicitante')->get();

        $user = DB::table('usuarios')->where('idUser', auth()->user()->id)->get();
        
        $lider = 0;
        if ($user[0]->nombre == "resolutor") {
            $resolutor = Resolutor::where('idUser', $user[0]->idUser)->first('lider');
            $lider = $resolutor->lider;           
        }                 
        if ($user[0]->nombre == "resolutor") {
            $user2 = Resolutor::where('idUser', $user[0]->idUser)->first();
        } else {
            $user2 = 0;
        }

        
        $request->user()->authorizeRoles(['solicitante', 'administrador', 'supervisor', 'resolutor', 'usuario', 'gestor']);

        $resolutors = Resolutor::where('rutEmpresa', auth()->user()->rutEmpresa)->get();
        $teams = Team::where('rutEmpresa', auth()->user()->rutEmpresa)->get();
        if (isset($request->state)) {
            $request->session()->put('state', $request->state);
        } else {
            $request->session()->put('state', "1");
            $state = 1;
        }
        if ($user[0]->nombre == "resolutor") 
        {
            $res = Resolutor::where('idUser', $user[0]->idUser)->first();
            if ($res->lider==1) 
            {
                $res = Resolutor::where('idUser', $user[0]->idUser)->first();
                $equipo = Team::where('id', $res->idTeam)->first();
                switch ($request->session()->get('state')) 
                {
                    case '1':
                    $req = DB::table('requerimientos_equipos')->where([
                        ['estado', 1],
                        ['aprobacion', '!=', '4'],
                        ['rutEmpresa', auth()->user()->rutEmpresa],
                        ['idEquipo', $equipo->id],
                    ])->get();
                    
                    if(count($req) != 0){
                        foreach ($req as $requerimiento)
                        {
                            $requerimiento = (array) $requerimiento;
                            $requerimiento ['tipo'] = "requerimiento";
                            $requerimient [] = $requerimiento;
                            $tareas = Tarea::where([
                                ['idRequerimiento', $requerimiento['id']],
                                ['estado', 1]
                            ])->get();
                            if(count($tareas) != 0)
                            {
                                foreach ($tareas as $tarea)
                                {
                                    $tarea ['tipo'] = "tarea";
                                    $requerimient [] = $tarea;
                                }
                            }
                        }

                        $requerimientos = [];
                        $estado = true;
                        $estatus = [];
                        $hoy = new DateTime();

                        foreach ($requerimient as $requerimiento) {
                            foreach ($anidados as $anidado) {
                                if ($anidado->idRequerimientoAnexo == $requerimiento['id']) {
                                    $estado = false;
                                }
                            }
                            if ($estado == true) {
                                if ($requerimiento['fechaCierre'] == "9999-12-31 00:00:00") {
                                    $requerimiento ['status'] = 1;
                                    $requerimiento = (object) $requerimiento;
                                    $requerimientos [] = $requerimiento;
                                } else
                                {
                                    if($requerimiento['fechaRealCierre'] != ""){
                                        $cierre = new Datetime($requerimiento['fechaRealCierre']);
                                    } else {
                                        $cierre = new DateTime($requerimiento['fechaCierre']);
                                    }
                                    
                                    if ($cierre->getTimestamp()<$hoy->getTimestamp()) {
                                        $requerimiento ['status'] = 3;
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
                                        } else {
                                            $requerimiento ['status'] = 1;
                                        }
                                        $variable = 0;
                                        unset($hoy);
                                        $hoy = new DateTime();                           
                                    }
                                    $requerimiento = (object) $requerimiento;
                                    $requerimientos [] = $requerimiento;
                                }                        
                            }                    
                            $estado = true;                    
                        }

                        $requerimientos = (object)$requerimientos;
                    } else {
                        $requerimientos = [];
                    }    
                    break;
                    case '0':
                    $req = DB::table('requerimientos_equipos')->where([
                        ['estado', '=', 2],
                        ['rutEmpresa', '=', auth()->user()->rutEmpresa],
                        ['idEquipo', $equipo->id],
                    ])->get();
                    $requerimientos = [];
                    $estado = true;
                    $estatus = [];
                    $hoy = new DateTime();

                    foreach ($req as $requerimiento) {
                        foreach ($anidados as $anidado) {
                            if ($anidado->idRequerimientoAnexo == $requerimiento->id) {
                                $estado = false;
                            }
                        }
                        if ($estado == true) {
                            $requerimientos [] = $requerimiento;
                        }                    
                        $estado = true;                    
                    }

                    $requerimientos = (object)$requerimientos;              
                    break;  
                    case '2':
                    $req = DB::table('requerimientos_equipos')->where([
                        ['estado', '=', 1],
                        ['aprobacion', '!=', 4],
                        ['porcentajeEjecutado', '>=', $request->valorN],
                        ['rutEmpresa', '=', auth()->user()->rutEmpresa],
                        ['idEquipo', $equipo->id],
                    ])->get();
                       
                    $requerimientos = [];
                    $estado = true;
                    $estatus = [];
                    $hoy = new DateTime();

                    foreach ($req as $requerimiento) 
                    {
                        $requerimiento = (array)$requerimiento;
                        $requerimiento['tipo'] = "requerimiento";
                        foreach ($anidados as $anidado) 
                        {
                            if ($anidado->idRequerimientoAnexo == $requerimiento['id']) 
                            {
                                $estado = false;
                            }
                        }
                        if ($estado == true) 
                        {
                            if ($requerimiento['fechaCierre'] == "9999-12-31 00:00:00") 
                            {
                                $requerimiento ['status'] = 1;  
                            } else
                            {
                                if($requerimiento['fechaRealCierre'] != ""){
                                    $cierre = new Datetime($requerimiento['fechaRealCierre']);
                                } else {
                                    $cierre = new DateTime($requerimiento['fechaCierre']);
                                }
                                if ($cierre->getTimestamp()<$hoy->getTimestamp()) 
                                {
                                    $requerimiento ['status'] = 3;
                                } else 
                                {
                                    $variable = 0;
                                    while ($hoy->getTimestamp() < $cierre->getTimestamp()) 
                                    {

                                        if ($hoy->format('l') == 'Saturday' or $hoy->format('l') == 'Sunday') 
                                        {
                                            $hoy->modify("+1 days");               
                                        }else
                                        {
                                            $variable++;
                                            $hoy->modify("+1 days");                       
                                        }                   
                                    }

                                    if ($variable<=3) 
                                    {
                                        $requerimiento ['status'] = 2;
                                    } else 
                                    {
                                        $requerimiento ['status'] = 1;
                                    }
                                    $variable = 0;
                                    unset($hoy);
                                    $hoy = new DateTime();                           
                                }
                                $requerimiento = (object) $requerimiento;
                                $requerimientos [] = $requerimiento;
                            }                        
                        }                    
                        $estado = true;                    
                    }
                    $requerimientos = (object)$requerimientos;               
                    break;
                    case '3':
                    $req = DB::table('requerimientos_equipos')->where([
                        ['estado', '=', 1],
                        ['aprobacion', '!=', 4],
                        ['porcentajeEjecutado', '<=', $request->valorN],
                        ['rutEmpresa', '=', auth()->user()->rutEmpresa],
                        ['idEquipo', $equipo->id],
                    ])->get();
                    $requerimientos = [];
                    $estado = true;
                    $estatus = [];
                    $hoy = new DateTime();
                    foreach ($req as $requerimiento) {
                        $requerimiento = (array)$requerimiento;
                        $requerimiento['tipo'] = "requerimiento";
                        foreach ($anidados as $anidado) {
                            if ($anidado->idRequerimientoAnexo == $requerimiento['id']) {
                                $estado = false;
                            }
                        }
                        if ($estado == true) {
                            if ($requerimiento['fechaCierre'] == "9999-12-31 00:00:00") {
                                $requerimiento ['status'] = 1;  
                            } else
                            {
                                if($requerimiento['fechaRealCierre'] != ""){
                                    $cierre = new Datetime($requerimiento['fechaRealCierre']);
                                } else {
                                    $cierre = new DateTime($requerimiento['fechaCierre']);
                                }
                                if ($cierre->getTimestamp()<$hoy->getTimestamp()) {
                                    $requerimiento ['status'] = 3;
                                } else 
                                {
                                    $variable = 0;
                                    while ($hoy->getTimestamp() < $cierre->getTimestamp()) {
                                        if ($hoy->format('l') == 'Saturday' or $hoy->format('l') == 'Sunday') {
                                            $hoy->modify("+1 days");               
                                        }else
                                        {
                                            $variable++;
                                            $hoy->modify("+1 days");                       
                                        }                   
                                    }
                                    if ($variable<=3)
                                    {
                                        $requerimiento ['status'] = 2;
                                    } else 
                                    {
                                        $requerimiento ['status'] = 1;
                                    }
                                    $variable = 0;
                                    unset($hoy);
                                    $hoy = new DateTime();                           
                                }
                                $requerimiento = (object) $requerimiento;
                                $requerimientos [] = $requerimiento;
                            }                        
                        }                    
                        $estado = true;                    
                    }
                    $requerimientos = (object)$requerimientos;               
                    break;
                    
                    case '4':
                    $req = DB::table('requerimientos_equipos')->where([
                        ['estado', '=', 1],
                        ['aprobacion', 3],
                        ['rutEmpresa', '=', auth()->user()->rutEmpresa],
                        ['idEquipo', $equipo->id],
                    ])->get();
                    
                    if(count($req) != 0){
                        foreach ($req as $requerimiento)
                        {
                            $requerimiento = (array) $requerimiento;
                            $requerimiento ['tipo'] = "requerimiento";
                            $requerimient [] = $requerimiento;
                            $tareas = Tarea::where([
                                ['idRequerimiento', $requerimiento['id']],
                                ['estado', 1],
                            ])->get();
                            if(count($tareas) != 0)
                            {
                                foreach ($tareas as $tarea)
                                {
                                    $tarea ['tipo'] = "tarea";
                                    $requerimient[] = $tarea;
                                }
                            }
                        }

                        $arreglo = [];
                        $requerimientos = [];
                        $estado = true;

                        $hoy = new DateTime();
                        foreach ($requerimient as $req2) 
                        {
                            foreach ($anidados as $anidado) {
                                if ($anidado->idRequerimientoAnexo == $req2['id']) {
                                    $estado = false;
                                }
                            }
                            if ($estado == true) {
                                $arreglo [] = $req2;
                            }
                            $estado = true;
                            $estatus = [];
                            $hoy = new DateTime();
                        }
                        $arreglo = (object)$arreglo;
                        foreach ($arreglo as $req2) {                
                            if ($req2['fechaRealCierre'] == null) 
                            {
                                if ($req2['fechaCierre'] != "9999-12-31 00:00:00") {
                                    $cierre = new DateTime($req2['fechaCierre']);
                                    
                                    if ($hoy->getTimestamp()>$cierre->getTimestamp()) 
                                    {
                                        $req2 ['status'] = 3;
                                        $req2 = (object) $req2;
                                        $requerimientos[] = $req2;                         
                                    }
                                }    
                            } else 
                            {
                                $cierre = new DateTime($req2['fechaRealCierre']);
                                if ($hoy->getTimestamp()>$cierre->getTimestamp()) 
                                {
                                    $req2 ['status'] = 3;
                                    $req2 = (object) $req2;
                                    $requerimientos[] = $req2;                         
                                }                     
                            }   
                        }

                        $requerimientos = (object)$requerimientos;
                    } else {
                        $requerimientos = [];
                    }    
                    break;
                    case '5':
                    $req = DB::table('requerimientos_equipos')->where([
                        ['estado', '=', 1],
                        ['aprobacion', 3],  
                        ['rutEmpresa', '=', auth()->user()->rutEmpresa],
                        ['idEquipo', $equipo->id],
                        ['idSolicitante', $request->solicitante],
                    ])->get();
                    $requerimientos = [];
                    $estado = true;
                    $estatus = [];
                    $hoy = new DateTime();
                    foreach ($req as $requerimiento) 
                    {
                        $requerimiento = (array)$requerimiento;
                        $requerimiento['tipo'] = "requerimiento";
                        foreach ($anidados as $anidado) {
                            if ($anidado->idRequerimientoAnexo == $requerimiento['id']) {
                                $estado = false;
                            }
                        }
                        if ($estado == true) {
                            if ($requerimiento['fechaCierre'] == "9999-12-31 00:00:00") {
                                $requerimiento ['status'] = 1;  
                            } else
                            {
                                if($requerimiento['fechaRealCierre'] != ""){
                                    $cierre = new Datetime($requerimiento['fechaRealCierre']);
                                } else {
                                    $cierre = new DateTime($requerimiento['fechaCierre']);
                                }
                                if ($cierre->getTimestamp()<$hoy->getTimestamp()) {
                                    $requerimiento ['status'] = 3;
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
                                    } else {
                                        $requerimiento ['status'] = 1;
                                    }
                                    $variable = 0;
                                    unset($hoy);
                                    $hoy = new DateTime();                           
                                }
                                $requerimiento = (object) $requerimiento;
                                $requerimientos [] = $requerimiento;
                            }                       
                        }                    
                        $estado = true;                    
                    }
                    $requerimientos = (object)$requerimientos;                 
                    break;
                    
                    case '6':   
                    $sol = Solicitante::where('idUser', $user[0]->idUser)->first();  
                    $req = DB::table('requerimientos_equipos')->where([
                        ['estado', 1],
                        ['rutEmpresa', '=', auth()->user()->rutEmpresa],
                        ['aprobacion','=',4],
                        ['idSolicitante', $sol->id]
                    ])->get();

                    $requerimientos = [];
                    $estado = true;
                    $estatus = [];
                    $hoy = new DateTime();
                    foreach ($req as $requerimiento) 
                    {
                        $requerimiento = (array)$requerimiento;
                        foreach ($anidados as $anidado) {
                            if ($anidado->idRequerimientoAnexo == $requerimiento['id']) {
                                $estado = false;
                            }
                        }
                        if ($estado == true) 
                        {
                            if ($requerimiento['fechaCierre'] == "9999-12-31 00:00:00") {
                                $requerimiento ['status'] = 1;  
                            } else
                            {
                                if($requerimiento['fechaRealCierre'] != ""){
                                    $cierre = new Datetime($requerimiento['fechaRealCierre']);
                                } else {
                                    $cierre = new DateTime($requerimiento['fechaCierre']);
                                }
                                if ($cierre->getTimestamp()<$hoy->getTimestamp()) {
                                    $requerimiento ['status'] = 3;
                                } else 
                                {
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
                                    if ($variable<=3) 
                                    {
                                        $requerimiento ['status'] = 2;
                                    } else {
                                        $requerimiento ['status'] = 1;
                                    }
                                    $variable = 0;
                                    unset($hoy);
                                    $hoy = new DateTime();                           
                                }
                                $requerimiento = (object) $requerimiento;
                                $requerimientos [] = $requerimiento;
                            }                        
                        }                    
                        $estado = true;                    
                    }
                    $requerimientos = (object)$requerimientos;              
                    break;
                    case '7':
                    $req = Requerimiento::orderBy('fechaSolicitud', 'desc')->where([
                        ['estado', 1],
                        ['aprobacion', 4],
                        ['rutEmpresa', '=', auth()->user()->rutEmpresa],
                        ['resolutor', $res->id],
                    ])->get();
                    $requerimientos = [];
                    $estado = true;
                    $estatus = [];
                    $hoy = new DateTime();

                    foreach ($req as $requerimiento) {
                        foreach ($anidados as $anidado) {
                            if ($anidado->idRequerimientoAnexo == $requerimiento->id) {
                                $estado = false;
                            }
                        }
                        if ($estado == true) {
                            if ($requerimiento->fechaCierre == "9999-12-31 00:00:00") {
                                $requerimiento ['status'] = 1;
                                $requerimientos [] = $requerimiento;
                            } else
                            {
                                if($requerimiento['fechaRealCierre'] != ""){
                                    $cierre = new Datetime($requerimiento['fechaRealCierre']);
                                } else {
                                    $cierre = new DateTime($requerimiento['fechaCierre']);
                                }
                                if ($cierre->getTimestamp()<$hoy->getTimestamp()) {
                                    $requerimiento ['status'] = 3;
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
                                    } else {
                                        $requerimiento ['status'] = 1;
                                    }
                                    $variable = 0;
                                    unset($hoy);
                                    $hoy = new DateTime();                           
                                }
                                $requerimientos [] = $requerimiento;
                            }                        
                        }                    
                        $estado = true;                    
                    }

                    $requerimientos = (object)$requerimientos; 
                    break;
                    case '8':
                    $req = Requerimiento::orderBy('fechaSolicitud', 'desc')->where([
                        ['estado', 1],
                        ['aprobacion', 2],
                        ['rutEmpresa', '=', auth()->user()->rutEmpresa],
                        ['resolutor', $res->id],
                    ])->get();
                    $requerimientos = [];
                    $estado = true;
                    $estatus = [];
                    $hoy = new DateTime();

                    foreach ($req as $requerimiento) {
                        foreach ($anidados as $anidado) {
                            if ($anidado->idRequerimientoAnexo == $requerimiento->id) {
                                $estado = false;
                            }
                        }
                        if ($estado == true) {
                            if ($requerimiento->fechaCierre == "9999-12-31 00:00:00") {
                                $requerimiento ['status'] = 1;
                                $requerimiento = (object) $requerimiento;
                                $requerimientos [] = $requerimiento;
                            } else
                            {
                                if($requerimiento['fechaRealCierre'] != ""){
                                    $cierre = new Datetime($requerimiento['fechaRealCierre']);
                                } else {
                                    $cierre = new DateTime($requerimiento['fechaCierre']);
                                }
                                if ($cierre->getTimestamp()<$hoy->getTimestamp()) {
                                    $requerimiento ['status'] = 3;
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
                                    } else {
                                        $requerimiento ['status'] = 1;
                                    }
                                    $variable = 0;
                                    unset($hoy);
                                    $hoy = new DateTime();                           
                                }
                                $requerimiento = (object) $requerimiento;
                                $requerimientos [] = $requerimiento;
                            }                        
                        }                    
                        $estado = true;                    
                    }

                    $requerimientos = (object)$requerimientos; 
                    break;
                    case '9':
                    $sol = Solicitante::where('idUser',$user[0]->idUser)->first();
                    if($sol != null){
                        $req = Requerimiento::where([
                            ['rutEmpresa', auth()->user()->rutEmpresa],
                            ['estado', '=', 1],
                            ['porcentajeEjecutado','<',100],
                            ['idSolicitante', $sol->id]
                        ])->get();                        
                    } else {
                        $req = [];
                    }
                    $requerimientos = [];
                    $estado = true;
                    $estatus = [];
                    $hoy = new DateTime();

                    foreach ($req as $requerimiento) {
                        foreach ($anidados as $anidado) {
                            if ($anidado->idRequerimientoAnexo == $requerimiento->id) {
                                $estado = false;
                            }
                        }
                        if ($estado == true) {
                            if ($requerimiento->fechaCierre == "9999-12-31 00:00:00") {
                                $requerimiento ['status'] = 1;
                                $requerimiento = (object) $requerimiento;
                                $requerimientos [] = $requerimiento;
                            } else
                            {
                                if($requerimiento['fechaRealCierre'] != ""){
                                    $cierre = new Datetime($requerimiento['fechaRealCierre']);
                                } else {
                                    $cierre = new DateTime($requerimiento['fechaCierre']);
                                }
                                if ($cierre->getTimestamp()<$hoy->getTimestamp()) {
                                    $requerimiento ['status'] = 3;
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
                                    } else {
                                        $requerimiento ['status'] = 1;
                                    }
                                    $variable = 0;
                                    unset($hoy);
                                    $hoy = new DateTime();                           
                                }
                                $requerimiento = (object) $requerimiento;
                                $requerimientos [] = $requerimiento;
                            }                        
                        }                    
                        $estado = true;                    
                    }

                    $requerimientos = (object)$requerimientos;                        
                    break;
                    
                    case '10':
                        $req = Requerimiento::where([
                            ['rutEmpresa', auth()->user()->rutEmpresa],
                            ['estado', '=', 1],
                            ['porcentajeEjecutado','<',100],
                            ['resolutor', $res->id]
                        ])->get();                        
                    $requerimientos = [];
                    $estado = true;
                    $estatus = [];
                    $hoy = new DateTime();

                    foreach ($req as $requerimiento) {
                        foreach ($anidados as $anidado) {
                            if ($anidado->idRequerimientoAnexo == $requerimiento->id) {
                                $estado = false;
                            }
                        }
                        if ($estado == true) {
                            if ($requerimiento->fechaCierre == "9999-12-31 00:00:00") {
                                $requerimiento ['status'] = 1;
                                $requerimiento = (object) $requerimiento;
                                $requerimientos [] = $requerimiento;
                            } else
                            {
                                if($requerimiento['fechaRealCierre'] != ""){
                                    $cierre = new Datetime($requerimiento['fechaRealCierre']);
                                } else {
                                    $cierre = new DateTime($requerimiento['fechaCierre']);
                                }
                                if ($cierre->getTimestamp()<$hoy->getTimestamp()) {
                                    $requerimiento ['status'] = 3;
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
                                    } else {
                                        $requerimiento ['status'] = 1;
                                    }
                                    $variable = 0;
                                    unset($hoy);
                                    $hoy = new DateTime();                           
                                }
                                $requerimiento = (object) $requerimiento;
                                $requerimientos [] = $requerimiento;
                            }                        
                        }                    
                        $estado = true;                    
                    }

                    $requerimientos = (object)$requerimientos;                        
                }                
            } else 
            {
                $res = Resolutor::where('idUser', $user[0]->idUser)->first();
                switch ($request->session()->get('state')) 
                {
                    case '1':
                    $req = Requerimiento::orderBy('fechaSolicitud', 'desc')->where([
                        ['estado', 1],
                        ['aprobacion', '!=', '4'],
                        ['rutEmpresa', auth()->user()->rutEmpresa],
                        ['resolutor', $res->id],
                    ])->get();
                    $requerimientos = [];
                    $estado = true;
                    $estatus = [];
                    $hoy = new DateTime();

                    foreach ($req as $requerimiento) {
                        foreach ($anidados as $anidado) {
                            if ($anidado->idRequerimientoAnexo == $requerimiento->id) {
                                $estado = false;
                            }
                        }
                        if ($estado == true) {
                            if ($requerimiento->fechaCierre == "9999-12-31 00:00:00") {
                                $requerimiento ['status'] = 1;
                                $requerimientos [] = $requerimiento;
                            } else
                            {
                                if($requerimiento['fechaRealCierre'] != ""){
                                    $cierre = new Datetime($requerimiento['fechaRealCierre']);
                                } else {
                                    $cierre = new DateTime($requerimiento['fechaCierre']);
                                }
                                if ($cierre->getTimestamp()<$hoy->getTimestamp()) {
                                    $requerimiento ['status'] = 3;
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
                                    } else {
                                        $requerimiento ['status'] = 1;
                                    }
                                    $variable = 0;
                                    unset($hoy);
                                    $hoy = new DateTime();                           
                                }
                                $requerimientos [] = $requerimiento;
                            }                        
                        }                    
                        $estado = true;                    
                    }

                    $requerimientos = (object)$requerimientos;                
                    break;
                    case '0':
                    $req = Requerimiento::orderBy('fechaSolicitud', 'desc')->where([
                        ['estado', '=', 2],
                        ['rutEmpresa', '=', auth()->user()->rutEmpresa],
                        ['resolutor', $res->id],
                    ])->get();
                    $requerimientos = [];
                    $estado = true;
                    $estatus = [];
                    $hoy = new DateTime();

                    foreach ($req as $requerimiento) {
                        foreach ($anidados as $anidado) {
                            if ($anidado->idRequerimientoAnexo == $requerimiento->id) {
                                $estado = false;
                            }
                        }
                        if ($estado == true) {
                            if ($requerimiento->fechaCierre == "9999-12-31 00:00:00") {
                                $requerimiento ['status'] = 1;  
                            } else
                            {
                                if($requerimiento['fechaRealCierre'] != ""){
                                    $cierre = new Datetime($requerimiento['fechaRealCierre']);
                                } else {
                                    $cierre = new DateTime($requerimiento['fechaCierre']);
                                }
                                if ($cierre->getTimestamp()<$hoy->getTimestamp()) {
                                    $requerimiento ['status'] = 3;
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
                                    } else {
                                        $requerimiento ['status'] = 1;
                                    }
                                    $variable = 0;
                                    unset($hoy);
                                    $hoy = new DateTime();                           
                                }
                                $requerimientos [] = $requerimiento;
                            }                        
                        }                    
                        $estado = true;                    
                    }

                    $requerimientos = (object)$requerimientos;              
                    break;  
                    case '2':
                    $req = Requerimiento::orderBy('fechaSolicitud', 'desc')->where([
                        ['estado', 1],
                        ['aprobacion', '!=', 4],
                        ['porcentajeEjecutado', '>=', $request->valorN],
                        ['rutEmpresa', '=', auth()->user()->rutEmpresa],
                        ['resolutor', $res->id],
                    ])->get();
                    $requerimientos = [];
                    $estado = true;
                    $estatus = [];
                    $hoy = new DateTime();

                    foreach ($req as $requerimiento) {
                        foreach ($anidados as $anidado) {
                            if ($anidado->idRequerimientoAnexo == $requerimiento->id) {
                                $estado = false;
                            }
                        }
                        if ($estado == true) {
                            if ($requerimiento->fechaCierre == "9999-12-31 00:00:00") {
                                $requerimiento ['status'] = 1;  
                            } else
                            {
                                if($requerimiento['fechaRealCierre'] != ""){
                                    $cierre = new Datetime($requerimiento['fechaRealCierre']);
                                } else {
                                    $cierre = new DateTime($requerimiento['fechaCierre']);
                                }
                                if ($cierre->getTimestamp()<$hoy->getTimestamp()) {
                                    $requerimiento ['status'] = 3;
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
                                    } else {
                                        $requerimiento ['status'] = 1;
                                    }
                                    $variable = 0;
                                    unset($hoy);
                                    $hoy = new DateTime();                           
                                }
                                $requerimientos [] = $requerimiento;
                            }                        
                        }                    
                        $estado = true;                    
                    }

                    $requerimientos = (object)$requerimientos;               
                    break;
                    case '3':
                    $req = Requerimiento::orderBy('fechaSolicitud', 'desc')->where([
                        ['estado', 1],
                        ['aprobacion', '!=', 4],
                        ['porcentajeEjecutado', '<=', $request->valorN],
                        ['rutEmpresa', '=', auth()->user()->rutEmpresa],
                        ['resolutor', $res->id],
                    ])->get();
                    $requerimientos = [];
                    $estado = true;
                    $estatus = [];
                    $hoy = new DateTime();

                    foreach ($req as $requerimiento) {
                        foreach ($anidados as $anidado) {
                            if ($anidado->idRequerimientoAnexo == $requerimiento->id) {
                                $estado = false;
                            }
                        }
                        if ($estado == true) {
                            if ($requerimiento->fechaCierre == "9999-12-31 00:00:00") {
                                $requerimiento ['status'] = 1;  
                            } else
                            {
                                if($requerimiento['fechaRealCierre'] != ""){
                                    $cierre = new Datetime($requerimiento['fechaRealCierre']);
                                } else {
                                    $cierre = new DateTime($requerimiento['fechaCierre']);
                                }
                                if ($cierre->getTimestamp()<$hoy->getTimestamp()) {
                                    $requerimiento ['status'] = 3;
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
                                    } else {
                                        $requerimiento ['status'] = 1;
                                    }
                                    $variable = 0;
                                    unset($hoy);
                                    $hoy = new DateTime();                           
                                }
                                $requerimientos [] = $requerimiento;
                            }                        
                        }                    
                        $estado = true;                    
                    }

                    $requerimientos = (object)$requerimientos;               
                    break;
                case '4':
                $req = Requerimiento::where([
                    ['estado', 1],
                    ['aprobacion', 3],
                    ['rutEmpresa', auth()->user()->rutEmpresa],
                    ['resolutor', $res->id],
                ])->get();
                $arreglo = [];
                $requerimientos = [];
                $estado = true;

                $hoy = new DateTime();
                foreach ($req as $req2) 
                {
                    foreach ($anidados as $anidado) {
                        if ($anidado->idRequerimientoAnexo == $req2->id) {
                            $estado = false;
                        }
                    }
                    if ($estado == true) {
                        $arreglo [] = $req2;
                    }
                    $estado = true;
                    $estatus = [];
                    $hoy = new DateTime();
                }
                $arreglo = (object)$arreglo;
                foreach ($arreglo as $req2) {                  
                    if ($req2['fechaRealCierre'] == null) 
                    {
                        if ($req2['fechaCierre'] != "9999-12-31 00:00:00") {
                            $cierre = new DateTime($req2['fechaCierre']);
                            
                            if ($hoy->getTimestamp()>$cierre->getTimestamp()) 
                            {
                                $req2 ['status'] = 3;
                                $req2 = (object) $req2;
                                $requerimientos[] = $req2;                         
                            }
                        }    
                    } else 
                    {
                        $cierre = new DateTime($req2['fechaRealCierre']);
                        if ($hoy->getTimestamp()>$cierre->getTimestamp()) 
                        {
                            $req2 ['status'] = 3;
                            $req2 = (object) $req2;
                            $requerimientos[] = $req2;                         
                        }                     
                    }  
                }

                $requerimientos = (object)$requerimientos;
                break;
                case '5':
                    $req = Requerimiento::where([
                        ['estado', 1],
                        ['aprobacion', 3],
                        ['resolutor', $res->id],
                        ['rutEmpresa', auth()->user()->rutEmpresa],
                        ['idSolicitante', $request->solicitante],        
                    ])->get();
                    $requerimientos = [];
                    $estado = true;
                    $estatus = [];
                    $hoy = new DateTime();

                    foreach ($req as $requerimiento) {
                        foreach ($anidados as $anidado) {
                            if ($anidado->idRequerimientoAnexo == $requerimiento->id) {
                                $estado = false;
                            }
                        }
                        if ($estado == true) {
                            if ($requerimiento->fechaCierre == "9999-12-31 00:00:00") {
                                $requerimiento ['status'] = 1;  
                            } else
                            {
                                if($requerimiento['fechaRealCierre'] != ""){
                                    $cierre = new Datetime($requerimiento['fechaRealCierre']);
                                } else {
                                    $cierre = new DateTime($requerimiento['fechaCierre']);
                                }
                                if ($cierre->getTimestamp()<$hoy->getTimestamp()) {
                                    $requerimiento ['status'] = 3;
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
                                    } else {
                                        $requerimiento ['status'] = 1;
                                    }
                                    $variable = 0;
                                    unset($hoy);
                                    $hoy = new DateTime();                           
                                }
                                $requerimientos [] = $requerimiento;
                            }                       
                        }                    
                        $estado = true;                    
                    }

                    $requerimientos = (object)$requerimientos;                 
                    break;
                    case '7':
                    $req = Requerimiento::orderBy('fechaSolicitud', 'desc')->where([
                        ['estado', 1],
                        ['aprobacion', 4],
                        ['rutEmpresa', '=', auth()->user()->rutEmpresa],
                        ['resolutor', $res->id],
                    ])->get();
                    $requerimientos = [];
                    $estado = true;
                    $estatus = [];
                    $hoy = new DateTime();

                    foreach ($req as $requerimiento) {
                        foreach ($anidados as $anidado) {
                            if ($anidado->idRequerimientoAnexo == $requerimiento->id) {
                                $estado = false;
                            }
                        }
                        if ($estado == true) {
                            if ($requerimiento->fechaCierre == "9999-12-31 00:00:00") {
                                $requerimiento ['status'] = 1;
                                $requerimientos [] = $requerimiento;
                            } else
                            {
                                if($requerimiento['fechaRealCierre'] != ""){
                                    $cierre = new Datetime($requerimiento['fechaRealCierre']);
                                } else {
                                    $cierre = new DateTime($requerimiento['fechaCierre']);
                                }
                                if ($cierre->getTimestamp()<$hoy->getTimestamp()) {
                                    $requerimiento ['status'] = 3;
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
                                    } else {
                                        $requerimiento ['status'] = 1;
                                    }
                                    $variable = 0;
                                    unset($hoy);
                                    $hoy = new DateTime();                           
                                }
                                $requerimientos [] = $requerimiento;
                            }                        
                        }                    
                        $estado = true;                    
                    }

                    $requerimientos = (object)$requerimientos; 
                    break;
                    case '8':
                    $req = Requerimiento::orderBy('fechaSolicitud', 'desc')->where([
                        ['estado', 1],
                        ['aprobacion', 2],
                        ['rutEmpresa', '=', auth()->user()->rutEmpresa],
                        ['resolutor', $res->id],
                    ])->get();
                    $requerimientos = [];
                    $estado = true;
                    $estatus = [];
                    $hoy = new DateTime();

                    foreach ($req as $requerimiento) {
                        foreach ($anidados as $anidado) {
                            if ($anidado->idRequerimientoAnexo == $requerimiento->id) {
                                $estado = false;
                            }
                        }
                        if ($estado == true) {
                            if ($requerimiento->fechaCierre == "9999-12-31 00:00:00") {
                                $requerimiento ['status'] = 1;
                                $requerimiento = (object) $requerimiento;
                                $requerimientos [] = $requerimiento;
                            } else
                            {
                                if($requerimiento['fechaRealCierre'] != ""){
                                    $cierre = new Datetime($requerimiento['fechaRealCierre']);
                                } else {
                                    $cierre = new DateTime($requerimiento['fechaCierre']);
                                }
                                if ($cierre->getTimestamp()<$hoy->getTimestamp()) {
                                    $requerimiento ['status'] = 3;
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
                                    } else {
                                        $requerimiento ['status'] = 1;
                                    }
                                    $variable = 0;
                                    unset($hoy);
                                    $hoy = new DateTime();                           
                                }
                                $requerimiento = (object) $requerimiento;
                                $requerimientos [] = $requerimiento;
                            }                        
                        }                    
                        $estado = true;                    
                    }

                    $requerimientos = (object)$requerimientos; 
                    break;
                    case '9':
                    $sol = Solicitante::where('idUser',$user[0]->idUser)->first();
                    if($sol != null){
                        $req = Requerimiento::where([
                            ['rutEmpresa', auth()->user()->rutEmpresa],
                            ['estado', '=', 1],
                            ['porcentajeEjecutado','<',100],
                            ['idSolicitante', $sol->id]
                        ])->get();                        
                    } else {
                        $req = [];
                    }

                    $requerimientos = [];
                    $estado = true;
                    $estatus = [];
                    $hoy = new DateTime();

                    foreach ($req as $requerimiento) {
                        foreach ($anidados as $anidado) {
                            if ($anidado->idRequerimientoAnexo == $requerimiento->id) {
                                $estado = false;
                            }
                        }
                        if ($estado == true) {
                            if ($requerimiento->fechaCierre == "9999-12-31 00:00:00") {
                                $requerimiento ['status'] = 1;
                                $requerimiento = (object) $requerimiento;
                                $requerimientos [] = $requerimiento;
                            } else
                            {
                                if($requerimiento['fechaRealCierre'] != ""){
                                    $cierre = new Datetime($requerimiento['fechaRealCierre']);
                                } else {
                                    $cierre = new DateTime($requerimiento['fechaCierre']);
                                }
                                if ($cierre->getTimestamp()<$hoy->getTimestamp()) {
                                    $requerimiento ['status'] = 3;
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
                                    } else {
                                        $requerimiento ['status'] = 1;
                                    }
                                    $variable = 0;
                                    unset($hoy);
                                    $hoy = new DateTime();                           
                                }
                                $requerimiento = (object) $requerimiento;
                                $requerimientos [] = $requerimiento;
                            }                        
                        }                    
                        $estado = true;                    
                    }

                    $requerimientos = (object)$requerimientos;                        
                    break;                    
                } 
            }
   
        }elseif ($user[0]->nombre == "solicitante") {
            $res = Resolutor::where('idUser', $user[0]->idUser)->first();
            $sol = Solicitante::where('idUser', $user[0]->idUser)->first();
            switch ($request->session()->get('state')) 
            {

                case '1':
                $requerimientos = Requerimiento::orderBy('fechaSolicitud', 'desc')->where([
                    ['estado', 1],
                    ['aprobacion', '!=', '4'],
                    ['rutEmpresa', auth()->user()->rutEmpresa],
                    ['idSolicitante', $sol->id],
                ])->get();
                    break;
                case '0':
                $requerimientos = Requerimiento::orderBy('fechaSolicitud', 'desc')->where([
                    ['estado', '=', 2],
                    ['rutEmpresa', '=', auth()->user()->rutEmpresa],
                    ['idSolicitante', $sol->id],
                ])->get();
                    break;  
                case '2':
                $requerimientos = Requerimiento::orderBy('fechaSolicitud', 'desc')->where([
                    ['estado', 1],
                    ['aprobacion', '!=', 4],
                    ['porcentajeEjecutado', '>=', $request->valorN],
                    ['rutEmpresa', '=', auth()->user()->rutEmpresa],
                ])->get();
                    break;
                case '3':
                $requerimientos = Requerimiento::orderBy('fechaSolicitud', 'desc')->where([
                    ['estado', 1],
                    ['aprobacion', '!=', 4],
                    ['porcentajeEjecutado', '<=', $request->valorN],
                    ['rutEmpresa', '=', auth()->user()->rutEmpresa],
                ])->get();
                    break;

                case '6':
                $req = DB::table('requerimientos_equipos')->where([
                    ['estado', 1],
                    ['rutEmpresa', '=', auth()->user()->rutEmpresa],
                    ['aprobacion', 4],
                    ['idSolicitante', $sol->id]
                ])->get();
                    
                $requerimientos = [];
                $estado = true;
                $estatus = [];
                $hoy = new DateTime();
                foreach ($req as $requerimiento) 
                {
                    $requerimiento = (array)$requerimiento;
                    foreach ($anidados as $anidado) {
                        if ($anidado->idRequerimientoAnexo == $requerimiento['id']) {
                            $estado = false;
                        }
                    }
                    if ($estado == true) 
                    {
                        if ($requerimiento['fechaCierre'] == "9999-12-31 00:00:00") {
                            $requerimiento ['status'] = 1;
                            $requerimiento = (object) $requerimiento;
                            $requerimientos [] = $requerimiento;                            
                        } else
                        {
                            if($requerimiento['fechaRealCierre'] != ""){
                                $cierre = new Datetime($requerimiento['fechaRealCierre']);
                            } else {
                                $cierre = new DateTime($requerimiento['fechaCierre']);
                            }
                            if ($cierre->getTimestamp()<$hoy->getTimestamp()) {
                                $requerimiento ['status'] = 3;
                            } else 
                            {
                                $variable = 0;
                                while ($hoy->getTimestamp() < $cierre->getTimestamp()) 
                                {
                                    if ($hoy->format('l') == 'Saturday' or $hoy->format('l') == 'Sunday') 
                                    {
                                        $hoy->modify("+1 days");               
                                    }else
                                    {
                                        $variable++;
                                        $hoy->modify("+1 days");                       
                                    }                   
                                }
                                if ($variable<=3) 
                                {
                                    $requerimiento ['status'] = 2;
                                } else {
                                    $requerimiento ['status'] = 1;
                                }
                                $variable = 0;
                                unset($hoy);
                                $hoy = new DateTime();                           
                            }
                            $requerimiento = (object) $requerimiento;
                            $requerimientos [] = $requerimiento;
                        }                        
                    }                    
                    $estado = true;                    
                }
                $requerimientos = (object)$requerimientos;                    
                break;    
            }
        } elseif ($user[0]->nombre == "supervisor") 
        {
            $res = Resolutor::where('idUser', $user[0]->idUser)->first();
            if (empty($res)){
                $res['id'] = 0;
                $res = (object)$res;
            }
            switch ($request->session()->get('state'))
            {
                case '1':
                $req = Requerimiento::where([
                    ['estado', 1],
                    ['aprobacion', '!=', '4'],
                    ['rutEmpresa', auth()->user()->rutEmpresa],
                ])->get();
                $requerimientos = [];
                $estado = true;
                $estatus = [];
                $hoy = new DateTime();

                foreach ($req as $requerimiento) {
                    foreach ($anidados as $anidado) {
                        if ($anidado->idRequerimientoAnexo == $requerimiento->id) {
                            $estado = false;
                        }
                    }
                    if ($estado == true) {
                        if ($requerimiento->fechaCierre == "9999-12-31 00:00:00") {
                            $requerimiento ['status'] = 1;
                            $requerimientos [] = $requerimiento;
                        } else
                        {
                            if($requerimiento['fechaRealCierre'] != ""){
                                $cierre = new Datetime($requerimiento['fechaRealCierre']);
                            } else {
                                $cierre = new DateTime($requerimiento['fechaCierre']);
                            }
                            if ($cierre->getTimestamp()<$hoy->getTimestamp()) {
                                $requerimiento ['status'] = 3;
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
                                } else {
                                    $requerimiento ['status'] = 1;
                                }
                                $variable = 0;
                                unset($hoy);
                                $hoy = new DateTime();                           
                            }
                            $requerimientos [] = $requerimiento;
                        }                        
                    }                    
                    $estado = true;                    
                }

                $requerimientos = (object)$requerimientos;                
                break;

                case '0':
                $req = Requerimiento::orderBy('fechaSolicitud', 'desc')->where([
                    ['estado', '=', 2],
                    ['rutEmpresa', '=', auth()->user()->rutEmpresa],
                ])->get();
                $requerimientos = [];
                $estado = true;
                $estatus = [];
                $hoy = new DateTime();

                foreach ($req as $requerimiento) {
                    foreach ($anidados as $anidado) {
                        if ($anidado->idRequerimientoAnexo == $requerimiento->id) {
                            $estado = false;
                        }
                    }
                    if ($estado == true) {
                        if ($requerimiento->fechaCierre == "9999-12-31 00:00:00") {
                            $requerimiento ['status'] = 1;
                            $requerimientos [] = $requerimiento;    
                        } else
                        {
                            if($requerimiento['fechaRealCierre'] != ""){
                                $cierre = new Datetime($requerimiento['fechaRealCierre']);
                            } else {
                                $cierre = new DateTime($requerimiento['fechaCierre']);
                            }
                            if ($cierre->getTimestamp()<$hoy->getTimestamp()) {
                                $requerimiento ['status'] = 3;
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
                                } else {
                                    $requerimiento ['status'] = 1;
                                }
                                $variable = 0;
                                unset($hoy);
                                $hoy = new DateTime();                           
                            }
                            $requerimientos [] = $requerimiento;
                        }                        
                    }                    
                    $estado = true;                    
                }

                $requerimientos = (object)$requerimientos;              
                break;

                case '2':
                $req = Requerimiento::orderBy('fechaSolicitud', 'desc')->where([
                    ['estado', 1],
                    ['aprobacion', '!=', 4],
                    ['porcentajeEjecutado', '>=', $request->valorN],
                    ['rutEmpresa', '=', auth()->user()->rutEmpresa],
                ])->get();
                $requerimientos = [];
                $estado = true;
                $estatus = [];
                $hoy = new DateTime();

                foreach ($req as $requerimiento) {
                    foreach ($anidados as $anidado) {
                        if ($anidado->idRequerimientoAnexo == $requerimiento->id) {
                            $estado = false;
                        }
                    }
                    if ($estado == true) {
                        if ($requerimiento->fechaCierre == "9999-12-31 00:00:00") {
                            $requerimiento ['status'] = 1;  
                        } else
                        {
                            if($requerimiento['fechaRealCierre'] != ""){
                                $cierre = new Datetime($requerimiento['fechaRealCierre']);
                            } else {
                                $cierre = new DateTime($requerimiento['fechaCierre']);
                            }
                            if ($cierre->getTimestamp()<$hoy->getTimestamp()) {
                                $requerimiento ['status'] = 3;
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
                                } else {
                                    $requerimiento ['status'] = 1;
                                }
                                $variable = 0;
                                unset($hoy);
                                $hoy = new DateTime();                           
                            }
                            $requerimientos [] = $requerimiento;
                        }                        
                    }                    
                    $estado = true;                    
                }

                $requerimientos = (object)$requerimientos;               
                break;

                case '3':
                $req = Requerimiento::orderBy('fechaSolicitud', 'desc')->where([
                    ['estado', 1],
                    ['aprobacion', '!=', 4],
                    ['porcentajeEjecutado', '<=', $request->valorN],
                    ['rutEmpresa', '=', auth()->user()->rutEmpresa],
                ])->get();
                $requerimientos = [];
                $estado = true;
                $estatus = [];
                $hoy = new DateTime();

                foreach ($req as $requerimiento) {
                    foreach ($anidados as $anidado) {
                        if ($anidado->idRequerimientoAnexo == $requerimiento->id) {
                            $estado = false;
                        }
                    }
                    if ($estado == true) {
                        if ($requerimiento->fechaCierre == "9999-12-31 00:00:00") {
                            $requerimiento ['status'] = 1;
                            $requerimientos [] = $requerimiento;    
                        } else
                        {
                            if($requerimiento['fechaRealCierre'] != ""){
                                $cierre = new Datetime($requerimiento['fechaRealCierre']);
                            } else {
                                $cierre = new DateTime($requerimiento['fechaCierre']);
                            }
                            if ($cierre->getTimestamp()<$hoy->getTimestamp()) {
                                $requerimiento ['status'] = 3;
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
                                } else {
                                    $requerimiento ['status'] = 1;
                                }
                                $variable = 0;
                                unset($hoy);
                                $hoy = new DateTime();                           
                            }
                            $requerimientos [] = $requerimiento;
                        }                        
                    }                    
                    $estado = true;                    
                }

                $requerimientos = (object)$requerimientos;               
                break;

                case '4':
                $req = Requerimiento::where([
                    ['estado', 1],
                    ['aprobacion', 3],
                    ['rutEmpresa', auth()->user()->rutEmpresa],
                ])->get();
                $arreglo = [];
                $requerimientos = [];
                $estado = true;

                $hoy = new DateTime();
                foreach ($req as $req2) 
                {
                    foreach ($anidados as $anidado) {
                        if ($anidado->idRequerimientoAnexo == $req2->id) {
                            $estado = false;
                        }
                    }
                    if ($estado == true) {
                        $arreglo [] = $req2;
                    }
                    $estado = true;
                    $estatus = [];
                    $hoy = new DateTime();
                }
                $arreglo = (object)$arreglo;
                foreach ($arreglo as $req2) {                
                    if ($req2['fechaRealCierre'] == null) 
                    {
                        if ($req2['fechaCierre'] != "9999-12-31 00:00:00") {
                            $cierre = new DateTime($req2['fechaCierre']);
                            
                            if ($hoy->getTimestamp()>$cierre->getTimestamp()) 
                            {
                                $req2 ['status'] = 3;
                                $req2 = (object) $req2;
                                $requerimientos[] = $req2;                         
                            }
                        }    
                    } else 
                    {
                        $cierre = new DateTime($req2['fechaRealCierre']);
                        if ($hoy->getTimestamp()>$cierre->getTimestamp()) 
                        {
                            $req2 ['status'] = 3;
                            $req2 = (object) $req2;
                            $requerimientos[] = $req2;                         
                        }                     
                    }   
                }

                $requerimientos = (object)$requerimientos;
                break;

                case '5':
                $req = Requerimiento::where([
                    ['estado', 1],
                    ['aprobacion', 3],
                    ['rutEmpresa', auth()->user()->rutEmpresa],
                    ['idSolicitante', $request->solicitante],        
                ])->get();
                $requerimientos = [];
                $estado = true;
                $estatus = [];
                $hoy = new DateTime();

                foreach ($req as $requerimiento) {
                    foreach ($anidados as $anidado) {
                        if ($anidado->idRequerimientoAnexo == $requerimiento->id) {
                            $estado = false;
                        }
                    }
                    if ($estado == true) {
                        if ($requerimiento->fechaCierre == "9999-12-31 00:00:00") {
                            $requerimiento ['status'] = 1;  
                        } else
                        {
                            if($requerimiento['fechaRealCierre'] != ""){
                                $cierre = new Datetime($requerimiento['fechaRealCierre']);
                            } else {
                                $cierre = new DateTime($requerimiento['fechaCierre']);
                            }
                            if ($cierre->getTimestamp()<$hoy->getTimestamp()) {
                                $requerimiento ['status'] = 3;
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
                                } else {
                                    $requerimiento ['status'] = 1;
                                }
                                $variable = 0;
                                unset($hoy);
                                $hoy = new DateTime();                           
                            }
                            $requerimientos [] = $requerimiento;
                        }                       
                    }                    
                    $estado = true;                    
                }

                $requerimientos = (object)$requerimientos;                 
                break;

                case '6':
                $req = DB::table('requerimientos_equipos')->where([
                    ['estado', 1],
                    ['rutEmpresa', '=', auth()->user()->rutEmpresa],
                    ['aprobacion', 4],
                    ['lider', 1],
                ])->get();
                $requerimientos = [];
                $estado = true;
                $estatus = [];
                $hoy = new DateTime();
                foreach ($req as $requerimiento) 
                {
                    $requerimiento = (array)$requerimiento;
                    foreach ($anidados as $anidado) {
                        if ($anidado->idRequerimientoAnexo == $requerimiento['id']) {
                            $estado = false;
                        }
                    }
                    if ($estado == true) 
                    {
                        if ($requerimiento['fechaCierre'] == "9999-12-31 00:00:00") {
                            $requerimiento ['status'] = 1;
                            $requerimiento = (object) $requerimiento;
                            $requerimientos [] = $requerimiento;                            
                        } else
                        {
                            if($requerimiento['fechaRealCierre'] != ""){
                                $cierre = new Datetime($requerimiento['fechaRealCierre']);
                            } else {
                                $cierre = new DateTime($requerimiento['fechaCierre']);
                            }
                            if ($cierre->getTimestamp()<$hoy->getTimestamp()) {
                                $requerimiento ['status'] = 3;
                            } else 
                            {
                                $variable = 0;
                                while ($hoy->getTimestamp() < $cierre->getTimestamp()) 
                                {
                                    if ($hoy->format('l') == 'Saturday' or $hoy->format('l') == 'Sunday') 
                                    {
                                        $hoy->modify("+1 days");               
                                    }else
                                    {
                                        $variable++;
                                        $hoy->modify("+1 days");                       
                                    }                   
                                }
                                if ($variable<=3) 
                                {
                                    $requerimiento ['status'] = 2;
                                } else {
                                    $requerimiento ['status'] = 1;
                                }
                                $variable = 0;
                                unset($hoy);
                                $hoy = new DateTime();                           
                            }
                            $requerimiento = (object) $requerimiento;
                            $requerimientos [] = $requerimiento;
                        }                        
                    }                    
                    $estado = true;                    
                }
                $requerimientos = (object)$requerimientos;              
                break;
                case '9':
                $sol = Solicitante::where('idUser',$user[0]->idUser)->first();
                if($sol != null){
                    $req = Requerimiento::where([
                        ['rutEmpresa', auth()->user()->rutEmpresa],
                        ['estado', '=', 1],
                        ['porcentajeEjecutado','<',100],
                        ['idSolicitante', $sol->id]
                    ])->get();                        
                } else {
                    $req = [];
                }
                $requerimientos = [];
                $estado = true;
                $estatus = [];
                $hoy = new DateTime();
                foreach ($req as $requerimiento) 
                {
                    foreach ($anidados as $anidado) {
                        if ($anidado->idRequerimientoAnexo == $requerimiento['id']) {
                            $estado = false;
                        }
                    }
                    if ($estado == true) 
                    {
                        if ($requerimiento['fechaCierre'] == "9999-12-31 00:00:00") {
                            $requerimiento ['status'] = 1;
                            $requerimiento = (object) $requerimiento;
                            $requerimientos [] = $requerimiento;                            
                        } else
                        {
                            if($requerimiento['fechaRealCierre'] != ""){
                                $cierre = new Datetime($requerimiento['fechaRealCierre']);
                            } else {
                                $cierre = new DateTime($requerimiento['fechaCierre']);
                            }
                            if ($cierre->getTimestamp()<$hoy->getTimestamp()) {
                                $requerimiento ['status'] = 3;
                            } else 
                            {
                                $variable = 0;
                                while ($hoy->getTimestamp() < $cierre->getTimestamp()) 
                                {
                                    if ($hoy->format('l') == 'Saturday' or $hoy->format('l') == 'Sunday') 
                                    {
                                        $hoy->modify("+1 days");               
                                    }else
                                    {
                                        $variable++;
                                        $hoy->modify("+1 days");                       
                                    }                   
                                }
                                if ($variable<=3) 
                                {
                                    $requerimiento ['status'] = 2;
                                } else {
                                    $requerimiento ['status'] = 1;
                                }
                                $variable = 0;
                                unset($hoy);
                                $hoy = new DateTime();                           
                            }
                            $requerimiento = (object) $requerimiento;
                            $requerimientos [] = $requerimiento;
                        }                        
                    }                    
                    $estado = true;                    
                }
                $requerimientos = (object)$requerimientos;                     
                break;
                case '10':
                    $req = Requerimiento::where([
                            ['rutEmpresa', auth()->user()->rutEmpresa],
                            ['estado', '=', 1],
                            ['resolutor', $res->id]
                        ])->get();

                    $requerimientos = [];
                    $estado = true;
                    $estatus = [];
                    $hoy = new DateTime();

                    foreach ($req as $requerimiento) {
                        foreach ($anidados as $anidado) {
                            if ($anidado->idRequerimientoAnexo == $requerimiento->id) {
                                $estado = false;
                            }
                        }
                        if ($estado == true) {
                            if ($requerimiento->fechaCierre == "9999-12-31 00:00:00") {
                                $requerimiento ['status'] = 1;
                                $requerimiento = (object) $requerimiento;
                                $requerimientos [] = $requerimiento;
                            } else
                            {
                                if($requerimiento['fechaRealCierre'] != ""){
                                    $cierre = new Datetime($requerimiento['fechaRealCierre']);
                                } else {
                                    $cierre = new DateTime($requerimiento['fechaCierre']);
                                }
                                if ($cierre->getTimestamp()<$hoy->getTimestamp()) {
                                    $requerimiento ['status'] = 3;
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
                                    } else {
                                        $requerimiento ['status'] = 1;
                                    }
                                    $variable = 0;
                                    unset($hoy);
                                    $hoy = new DateTime();                           
                                }
                                $requerimiento = (object) $requerimiento;
                                $requerimientos [] = $requerimiento;
                            }                        
                        }                    
                        $estado = true;                    
                    }

                    $requerimientos = (object)$requerimientos;                 
            }
        } 
        else 
        {
            $res = Resolutor::where('idUser', $user[0]->idUser)->first();
            switch ($request->session()->get('state')) 
            {
                case '1':
                $req = DB::table('requerimientos_equipos')->where([
                    ['estado', 1],
                    ['aprobacion', '!=', '4'],
                    ['rutEmpresa', auth()->user()->rutEmpresa],
                ])->get();
                        
                if(count($req) != 0){
                    foreach ($req as $requerimiento)
                    {
                        $requerimiento = (array) $requerimiento;
                        $requerimiento ['tipo'] = "requerimiento";
                        $requerimient [] = $requerimiento;
                        $tareas = Tarea::where([
                            ['idRequerimiento', $requerimiento['id']],
                            ['estado', 1]
                        ])->get();
                        if(count($tareas) != 0)
                        {
                            foreach ($tareas as $tarea)
                            {
                                $tarea ['tipo'] = "tarea";
                                $requerimient [] = $tarea;
                            }
                        }
                    }

                    $requerimientos = [];
                    $estado = true;
                    $estatus = [];
                    $hoy = new DateTime();


                    foreach ($requerimient as $requerimiento) {
                        $estado = true;
                        foreach ($anidados as $anidado) {
                            if ($anidado->idRequerimientoAnexo == $requerimiento['id']) {
                                $estado = false;
                            }
                        }
                        if ($estado == true) {
                            if ($requerimiento['fechaCierre'] == "9999-12-31 00:00:00") {
                                if($requerimiento['fechaRealCierre'] != ""){
                                    $cierre = new DateTime($requerimiento['fechaRealCierre']);
                                    
                                    if ($cierre->getTimestamp()<$hoy->getTimestamp()) {
                                        $requerimiento ['status'] = 3;
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
                                        } else {
                                            $requerimiento ['status'] = 1;
                                        }
                                        $variable = 0;
                                        unset($hoy);
                                        $hoy = new DateTime();                           
                                    }                                    
                                } else {
                                    $requerimiento ['status'] = 1;
                                    $requerimiento = (object) $requerimiento;
                                    $requerimientos [] = $requerimiento;                                    
                                }

                            } else
                            {
                                if($requerimiento['fechaRealCierre'] != ""){
                                    $cierre = new Datetime($requerimiento['fechaRealCierre']);
                                } else {
                                    $cierre = new DateTime($requerimiento['fechaCierre']);
                                }
                                    if ($cierre->getTimestamp()<$hoy->getTimestamp()) {
                                        $requerimiento ['status'] = 3;
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
                                        } else {
                                            $requerimiento ['status'] = 1;
                                        }
                                        $variable = 0;
                                        unset($hoy);
                                        $hoy = new DateTime();                           
                                    }
                                    $requerimiento = (object) $requerimiento;
                                    $requerimientos [] = $requerimiento;
                            }                        
                        }                    
                        $estado = true;                    
                    }

                    $requerimientos = (object)$requerimientos;
                } else {
                    $requerimientos = [];
                }    
                break;
                case '0':
                $req = Requerimiento::orderBy('fechaSolicitud', 'desc')->where([
                    ['estado', '=', 2],
                    ['rutEmpresa', '=', auth()->user()->rutEmpresa],
                ])->get();
                
                if(count($req) != 0){    
                    $requerimientos = [];
                    $estado = true;
                    $estatus = [];
                    $hoy = new DateTime();

                    foreach ($req as $requerimiento) {
                        foreach ($anidados as $anidado) {
                            if ($anidado->idRequerimientoAnexo == $requerimiento->id) {
                                $estado = false;
                            }
                        }
                        if ($estado == true) {
                            if ($requerimiento->fechaCierre == "9999-12-31 00:00:00") {
                                    $requerimiento ['status'] = 1;
                                $requerimientos [] = $requerimiento;	
                            } else
                            {
                                if($requerimiento['fechaRealCierre'] != ""){
                                    $cierre = new Datetime($requerimiento['fechaRealCierre']);
                                } else {
                                    $cierre = new DateTime($requerimiento['fechaCierre']);
                                }
                                    if ($cierre->getTimestamp()<$hoy->getTimestamp()) {
                                        $requerimiento ['status'] = 3;
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
                                        } else {
                                            $requerimiento ['status'] = 1;
                                        }
                                        $variable = 0;
                                        unset($hoy);
                                        $hoy = new DateTime();                           
                                    }
                                    $requerimientos [] = $requerimiento;
                            }                        
                        }                    
                        $estado = true;                    
                    }

                    $requerimientos = (object)$requerimientos;
                } else {
                    $requerimientos = [];
                }    
                break;  
                case '2':
                $req = Requerimiento::orderBy('fechaSolicitud', 'desc')->where([
                    ['estado', 1],
                    ['aprobacion', '!=', 4],
                    ['porcentajeEjecutado', '>=', $request->valorN],
                    ['rutEmpresa', '=', auth()->user()->rutEmpresa],
                ])->get();
                
                $requerimientos = [];
                $estado = true;
                $estatus = [];
                $hoy = new DateTime();

                foreach ($req as $requerimiento) {
                    foreach ($anidados as $anidado) {
                        if ($anidado->idRequerimientoAnexo == $requerimiento->id) {
                            $estado = false;
                        }
                    }
                    if ($estado == true) {
                    	if ($requerimiento->fechaCierre == "9999-12-31 00:00:00") {
                    		$requerimiento ['status'] = 1;	
                    	} else
                    	{
                            if($requerimiento['fechaRealCierre'] != ""){
                                $cierre = new Datetime($requerimiento['fechaRealCierre']);
                            } else {
                                $cierre = new DateTime($requerimiento['fechaCierre']);
                            }
	                        if ($cierre->getTimestamp()<$hoy->getTimestamp()) {
	                            $requerimiento ['status'] = 3;
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
	                            } else {
	                                $requerimiento ['status'] = 1;
	                            }
	                            $variable = 0;
	                            unset($hoy);
	                            $hoy = new DateTime();                           
	                        }
	                        $requerimientos [] = $requerimiento;
                        }                        
                    }                    
                    $estado = true;                    
                }

                $requerimientos = (object)$requerimientos;               
                break;
                case '3':
                $req = Requerimiento::orderBy('fechaSolicitud', 'desc')->where([
                    ['estado', 1],
                    ['aprobacion', '!=', 4],
                    ['porcentajeEjecutado', '<=', $request->valorN],
                    ['rutEmpresa', '=', auth()->user()->rutEmpresa],
                ])->get();
                $requerimientos = [];
                $estado = true;
                $estatus = [];
                $hoy = new DateTime();

                foreach ($req as $requerimiento) {
                    foreach ($anidados as $anidado) {
                        if ($anidado->idRequerimientoAnexo == $requerimiento->id) {
                            $estado = false;
                        }
                    }
                    if ($estado == true) {
                    	if ($requerimiento->fechaCierre == "9999-12-31 00:00:00") {
                    		$requerimiento ['status'] = 1;
                            $requerimientos [] = $requerimiento;	
                    	} else
                    	{
                            if($requerimiento['fechaRealCierre'] != ""){
                                $cierre = new Datetime($requerimiento['fechaRealCierre']);
                            } else {
                                $cierre = new DateTime($requerimiento['fechaCierre']);
                            }
	                        if ($cierre->getTimestamp()<$hoy->getTimestamp()) {
	                            $requerimiento ['status'] = 3;
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
	                            } else {
	                                $requerimiento ['status'] = 1;
	                            }
	                            $variable = 0;
	                            unset($hoy);
	                            $hoy = new DateTime();                           
	                        }
	                        $requerimientos [] = $requerimiento;
                        }                        
                    }                    
                    $estado = true;                    
                }

                $requerimientos = (object)$requerimientos;               
                break;
                case '4':
                $req = DB::table('requerimientos_equipos')->where([
                    ['estado', 1],
                    ['aprobacion', 3],
                    ['rutEmpresa', auth()->user()->rutEmpresa],
                ])->get();
                    
                
                if(count($req) != 0){
                    foreach ($req as $requerimiento)
                    {
                        $requerimiento = (array) $requerimiento;
                        $requerimiento ['tipo'] = "requerimiento";
                        $requerimient [] = $requerimiento;
                        $tareas = Tarea::where([
                            ['idRequerimiento', $requerimiento['id']],
                            ['estado', 1]
                        ])->get();
                        if(count($tareas) != 0)
                        {
                            foreach ($tareas as $tarea)
                            {
                                $tarea ['tipo'] = "tarea";
                                $requerimient[] = $tarea;
                            }
                        }
                    }

                    $arreglo = [];
                    $requerimientos = [];
                    $estado = true;

                    $hoy = new DateTime();
                    
                    foreach ($requerimient as $req2) 
                    {
                        foreach ($anidados as $anidado) {
                            if ($anidado->idRequerimientoAnexo == $req2['id']) {
                                $estado = false;
                            }
                        }
                        if ($estado == true) {
                            $arreglo [] = $req2;
                        }
                        $estado = true;
                        $estatus = [];
                        $hoy = new DateTime();
                    }
                    $arreglo = (object)$arreglo;
                    foreach ($arreglo as $req2) {
                        if ($req2['fechaRealCierre'] == null) 
                        {
                            if ($req2['fechaCierre'] != "9999-12-31 00:00:00") {
                                $cierre = new DateTime($req2['fechaCierre']);
                                
                                if ($hoy->getTimestamp()>$cierre->getTimestamp()) 
                                {
                                    $req2 ['status'] = 3;
                                    $req2 = (object) $req2;
                                    $requerimientos[] = $req2;                         
                                }
                            } else {
                                $req2 ['status'] = 3;
                                $req2 = (object) $req2;
                                $requerimientos[] = $req2;                                  
                            }    
                        } else 
                        {
                            $cierre = new DateTime($req2['fechaRealCierre']);
                            if ($hoy->getTimestamp()>$cierre->getTimestamp()) 
                            {
                                $req2 ['status'] = 3;
                                $req2 = (object) $req2;
                                $requerimientos[] = $req2;                         
                            }                     
                        }   
                    }

                    $requerimientos = (object)$requerimientos;
                } else {
                    $requerimientos = [];
                }    
                break;
                case '5':
                $req = Requerimiento::where([
                    ['estado', 1],
                    ['aprobacion', 3],
                    ['rutEmpresa', auth()->user()->rutEmpresa],
                    ['idSolicitante', $request->solicitante],        
                ])->get();
                $requerimientos = [];
                $estado = true;
                $estatus = [];
                $hoy = new DateTime();

                foreach ($req as $requerimiento) {
                    foreach ($anidados as $anidado) {
                        if ($anidado->idRequerimientoAnexo == $requerimiento->id) {
                            $estado = false;
                        }
                    }
                    if ($estado == true) {
                    	if ($requerimiento->fechaCierre == "9999-12-31 00:00:00") {
                    		$requerimiento ['status'] = 1;	
                    	} else
                    	{
                            if($requerimiento['fechaRealCierre'] != ""){
                                $cierre = new Datetime($requerimiento['fechaRealCierre']);
                            } else {
                                $cierre = new DateTime($requerimiento['fechaCierre']);
                            }
	                        if ($cierre->getTimestamp()<$hoy->getTimestamp()) {
	                            $requerimiento ['status'] = 3;
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
	                            } else {
	                                $requerimiento ['status'] = 1;
	                            }
	                            $variable = 0;
	                            unset($hoy);
	                            $hoy = new DateTime();                           
	                        }
	                        $requerimientos [] = $requerimiento;
                        }                       
                    }                    
                    $estado = true;                    
                }

                $requerimientos = (object)$requerimientos;                 
                break;
                case '6':
                $req = DB::table('requerimientos_equipos')->where([
                    ['estado', 1],
                    ['rutEmpresa', '=', auth()->user()->rutEmpresa],
                    ['aprobacion', 4],
                ])->get();
                $requerimientos = [];
                $estado = true;
                $estatus = [];
                $hoy = new DateTime();
                foreach ($req as $requerimiento) 
                {
                    $requerimiento = (array)$requerimiento;
                    $requerimiento ['tipo'] = "requerimiento";
                    foreach ($anidados as $anidado) {
                        if ($anidado->idRequerimientoAnexo == $requerimiento['id']) {
                            $estado = false;
                        }
                    }
                    if ($estado == true) 
                    {
                        if ($requerimiento['fechaCierre'] == "9999-12-31 00:00:00") {
                            $requerimiento ['status'] = 1;  
                        } else
                        {
                            if($requerimiento['fechaRealCierre'] != ""){
                                $cierre = new Datetime($requerimiento['fechaRealCierre']);
                            } else {
                                $cierre = new DateTime($requerimiento['fechaCierre']);
                            }
                            if ($cierre->getTimestamp()<$hoy->getTimestamp()) {
                                $requerimiento ['status'] = 3;
                            } else 
                            {
                                $variable = 0;
                                while ($hoy->getTimestamp() < $cierre->getTimestamp()) 
                                {
                                    if ($hoy->format('l') == 'Saturday' or $hoy->format('l') == 'Sunday') 
                                    {
                                        $hoy->modify("+1 days");               
                                    }else
                                    {
                                        $variable++;
                                        $hoy->modify("+1 days");                       
                                    }                   
                                }
                                if ($variable<=3) 
                                {
                                    $requerimiento ['status'] = 2;
                                } else {
                                    $requerimiento ['status'] = 1;
                                }
                                $variable = 0;
                                unset($hoy);
                                $hoy = new DateTime();                           
                            }
                            $requerimiento = (object) $requerimiento;
                            $requerimientos [] = $requerimiento;
                        }                        
                    }                    
                    $estado = true;                    
                }
                $requerimientos = (object)$requerimientos;              
                break;

                    case '8':
                    $req = Requerimiento::orderBy('fechaSolicitud', 'desc')->where([
                        ['estado', 1],
                        ['aprobacion', 2],
                        ['rutEmpresa', '=', auth()->user()->rutEmpresa],
                    ])->get();
                    $requerimientos = [];
                    $estado = true;
                    $estatus = [];
                    $hoy = new DateTime();

                    foreach ($req as $requerimiento) {
                        foreach ($anidados as $anidado) {
                            if ($anidado->idRequerimientoAnexo == $requerimiento->id) {
                                $estado = false;
                            }
                        }
                        if ($estado == true) {
                            if ($requerimiento->fechaCierre == "9999-12-31 00:00:00") {
                                $requerimiento ['status'] = 1;
                                $requerimiento = (object) $requerimiento;
                                $requerimientos [] = $requerimiento;
                            } else
                            {
                                if($requerimiento['fechaRealCierre'] != ""){
                                    $cierre = new Datetime($requerimiento['fechaRealCierre']);
                                } else {
                                    $cierre = new DateTime($requerimiento['fechaCierre']);
                                }
                                if ($cierre->getTimestamp()<$hoy->getTimestamp()) {
                                    $requerimiento ['status'] = 3;
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
                                    } else {
                                        $requerimiento ['status'] = 1;
                                    }
                                    $variable = 0;
                                    unset($hoy);
                                    $hoy = new DateTime();                           
                                }
                                $requerimiento = (object) $requerimiento;
                                $requerimientos [] = $requerimiento;
                            }                        
                        }                    
                        $estado = true;                    
                    }

                    $requerimientos = (object)$requerimientos; 
                    break;                                                 
            }            
        }          

        $valor = 1;
        if ($request->session()->get('state') == 1) {
            $valor = 1;
        }else {
            $valor = 0;
        }

        return view('Requerimientos.index', compact('requerimientos', 'resolutors', 'teams', 'valor', 'user', 'anidados', 'solicitantes', 'state', 'res', 'user2', 'lider'));

    }

    public function getResolutors(Request $request)
    {
        if ($request->ajax()) {
            $resolutors = Resolutor::where('idTeam', $request->id_team)->get();
            foreach ($resolutors as $resolutor) {
                $resolutorArray[$resolutor->id] = $resolutor->nombreResolutor;
            }

            return response()->json($resolutorArray);
        }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = DB::table('usuarios')->where('idUser', auth()->user()->id)->get();
        $lider = 0;
        if ($user[0]->nombre == "resolutor") {
            $resolutor = Resolutor::where('idUser', $user[0]->idUser)->first('lider');
            $lider = $resolutor->lider;           
        }

        $gestores = Resolutor::where([
            ['rutEmpresa', auth()->user()->rutEmpresa],
            ['lider', 1]
        ])->get();        

        auth()->user()->authorizeRoles(['administrador', 'solicitante', 'resolutor']);    

        $resolutors = Resolutor::where('rutEmpresa', auth()->user()->rutEmpresa)->orderBy('nombreResolutor')->get();
        $priorities = Priority::where('rutEmpresa', auth()->user()->rutEmpresa)->orderBy('namePriority')->get();
        $solicitantes = Solicitante::where('rutEmpresa', auth()->user()->rutEmpresa)->orderBy('nombreSolicitante')->get();

        $teams = Team::where('rutEmpresa', auth()->user()->rutEmpresa)->get();

        return view('Requerimientos.create', compact('resolutors', 'priorities', 'solicitantes', 'user', 'teams', 'gestores', 'lider'));        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->files);
        
        auth()->user()->authorizeRoles(['administrador', 'solicitante', 'resolutor']);
        $user = DB::table('usuarios')->where('idUser', auth()->user()->id)->get();
        $sol = Solicitante::where('idUser', $user[0]->idUser)->first();

            $data = request()->validate([
                'textoRequerimiento' => 'required',
                'comentario' => 'nullable',
                'fechaEmail' => 'required',
                'fechaSolicitud' => 'required',
                'fechaCierre' => 'nullable',
                'fechaRealCierre' => 'nullable',
                'numeroCambios' => 'nullable',
                'porcentajeEjecutado' => 'nullable',
                'idGestor' => 'required',
                'cierre' => 'nullable',
                'idPrioridad' => 'required',
                'textAvance' => 'nullable',
                'idSolicitante' => 'nullable',
                'idResolutor' => 'required'],
                ['nombreResolutor.required' => 'El campo nombre es obligatorio'],
                ['fechaEmail.required' => 'La fecha de email es obligatoria'],
                ['fechaSolicitud' => 'La fecha de solicitud es obligatoria'],
                ['fechaCierre' => 'La fecha de cierre es obligatoria']);

        if ($user[0]->nombre == 'solicitante') {
            $data['idSolicitante'] = $sol->id;
        }

        if ($data['fechaCierre'] == null) {
            $variable = new DateTime($data['fechaSolicitud']);
            $intervalo = new DateInterval('P1M');
            $variable->add($intervalo);
            $data['fechaCierre'] = $variable->format('Y-m-d');
        } else {
            $variable = new DateTime($data['fechaCierre']);
            $data['fechaCierre'] = $variable->format('Y-m-d');
        }

        $variable = new DateTime($data['fechaSolicitud']);
        $data['fechaSolicitud'] = $variable->format('Y-m-d');

        $variable = new DateTime($data['fechaEmail']);
        $data['fechaEmail'] = $variable->format('Y-m-d');

        if (isset($data['fechaRealCierre'])) {
        $variable = new DateTime($data['fechaRealCierre']);
        $data['fechaRealCierre'] = $variable->format('Y-m-d'); 
        }
        
        $fechaSoli = new DateTime($data['fechaSolicitud']);
        $fechaCie = new DateTime($data['fechaCierre']);
        
        if ($fechaCie >= $fechaSoli) 
        {
                $resolutor = Resolutor::where([
                    ['rutEmpresa', auth()->user()->rutEmpresa],
                    ['id', $data['idResolutor']],
                ])->get();

                $team = Team::where([
                    ['rutEmpresa', auth()->user()->rutEmpresa],
                    ['id',$resolutor[0]->idTeam],
                ])->get(); 

                $resolutors = Resolutor::where([
                ['rutEmpresa', auth()->user()->rutEmpresa],
                ])->get();

                $requerimientos = Requerimiento::where([
                ['rutEmpresa', auth()->user()->rutEmpresa],
                ])->get();
                
                $ultimo_req = DB::table('requerimientos_equipos')
                        ->where([
                            ['rutEmpresa', auth()->user()->rutEmpresa],
                            ['idEquipo', $team[0]->id]
                        ])->orderBy('created_at','DESC')->first();
                
                if($ultimo_req != null){
                   $ultimo_req_n = intval(substr($ultimo_req->id2, -3)); 
                } else{
                    $ultimo_req_n = 0;
                }

                $conteoA = $ultimo_req_n+1;

                if($conteoA<10){
                    $conteoA = "00".$conteoA;
                } else if($conteoA > 10 and $conteoA<100){
                    $conteoA = "0".$conteoA;
                } else {
                    $conteoA = $conteoA;
                }

                if ($request->idTipo == 1) {
                    $var = "RQ-".$team[0]->id2."-".$conteoA;
                } else {
                    $var = "INC-".$team[0]->id2."-".$conteoA;
                }
                
                $variable = new DateTime($data['fechaCierre']);
                $intervalo = new DateInterval('PT23H59M59S');
                $variable->add($intervalo);
                $data['fechaCierre'] = $variable;                
                
                Requerimiento::create([
                    'textoRequerimiento' => preg_replace("/[\r\n|\n|\r|\t]+/", " ", $data['textoRequerimiento']),
                    'comentario' => preg_replace("/[\r\n|\n|\r|\t]+/", " ", $data['comentario']),            
                    'fechaEmail' => $data['fechaEmail'],
                    'fechaSolicitud' => $data['fechaSolicitud'],
                    'fechaCierre' => $data['fechaCierre'],
                    'gestor' => $data['idGestor'],
                    'idSolicitante' => $data['idSolicitante'],
                    'idPrioridad' => $data['idPrioridad'],
                    'resolutor' => $data['idResolutor'],
                    'rutEmpresa' => auth()->user()->rutEmpresa,
                    'id2' => $var,
                    'aprobacion' => 3,
                ]);

            $req = Requerimiento::where('textoRequerimiento', $data['textoRequerimiento'])->get();

            $user = User::where([
                ['name', auth()->user()->name],
                ['rutEmpresa', auth()->user()->rutEmpresa],
            ])->get();

            LogRequerimientos::create([
                'idRequerimiento' => $req[0]->id,
                'idUsuario' => $user[0]->id,
                'tipo' => 'creacion',
            ]);

            if ($data['textAvance'] != null) {
                $guardado = Requerimiento::where([
                    ['textoRequerimiento', $data['textoRequerimiento']],
                    ['fechaEmail', $data['fechaEmail']],
                    ['fechaSolicitud', $data['fechaSolicitud']],
                ])->first();

                Avance::create([
                    'textAvance' => $data['textAvance'],
                    'fechaAvance' => Carbon::now(),
                    'idRequerimiento' => $guardado->id
                ]);
            }
            $requerimiento = Requerimiento::where('id2', $var)->first();
            $resolutor = Resolutor::where('id', $requerimiento->resolutor)->first();
            $obj = new \stdClass();
            $obj->idReq = $requerimiento->id2;
            $obj->id = $requerimiento->id;
            $obj->sol = $requerimiento->textoRequerimiento;
            $obj->nombre = $resolutor->nombreResolutor;

            $recep = $resolutor->email;
            
            //$request->user()->notify(new EnvioWhatsapp($requerimiento));
            
            // Guarda el documento adjunto al registrar el requerimiento
            $path = public_path().'/docs/requerimientos/';
            $file = $request->file('files');
            
            if (!empty($_FILES['archivo']['tmp_name']) || is_uploaded_file($_FILES['archivo']['tmp_name']))
            {
                $file = Input::file('archivo'); 
                $filenameWithExt = $request->file('archivo')->getClientOriginalName(); 
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension = $request->file('archivo')->getClientOriginalExtension();
                $filenameToStore = "Req".$requerimiento->id."_".uniqid().".".$extension;
                \Request::file('archivo')->move($path, $filenameToStore);
            }
            
            Notification::route('mail', $recep)->notify(new NewReqResolutor($obj));
            
            if ($request->idTipo == 1) {
                return redirect('requerimientos')->with('msj', 'Requerimiento '.$requerimiento->id2.' guardado correctamente');
            } else {
                return redirect('requerimientos')->with('msj', 'Incidente '.$requerimiento->id2.' guardado correctamente');
            }
        } else 
        {
            return back()->with('msj', 'La fecha de cierre del requerimiento debe ser mayor a la fecha de solicitud');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Requerimiento  $requerimiento
     * @return \Illuminate\Http\Response
     */
    public function show(Requerimiento $requerimiento)
    {
        $user = DB::table('usuarios')->where('idUser', auth()->user()->id)->get();
        $res = Resolutor::where('idUser', $user[0]->idUser)->first();
        if (empty($res)){
            $res['id']=0;
            $res = (object)$res;
        }
        $id2 = substr($requerimiento->id2,0,3);
        $lider = 0;
        if ($user[0]->nombre == "resolutor") {
            $resolutor = Resolutor::where('idUser', $user[0]->idUser)->first('lider');
            $lider = $resolutor->lider;           
        }        
        $solicitante = Solicitante::where([
            ['rutEmpresa', auth()->user()->rutEmpresa],
            ['id', $requerimiento->idSolicitante],
        ])->first();
        $resolutor = Resolutor::where([
            ['rutEmpresa', auth()->user()->rutEmpresa],
            ['id', $requerimiento->resolutor],
        ])->first();
        $user = DB::table('usuarios')->where('idUser', auth()->user()->id)->get();
        auth()->user()->authorizeRoles(['administrador', 'solicitante', 'resolutor', 'supervisor', 'gestor']);          
        $tareas = Tarea::where([
            ['idRequerimiento', $requerimiento->id],
        ])->get();
        $resolutores = [];
        foreach ($tareas as $tarea) 
        {
            $resolutor2 = Resolutor::where('id', $tarea->resolutor)->first();
            $resolutores [] = $resolutor2;
        }
        $resolutores = (object)$resolutores;
        $avances = Avance::where('idRequerimiento', $requerimiento->id)->latest('created_at')->paginate(5);
        $resolutors = Resolutor::where('rutEmpresa', auth()->user()->rutEmpresa)->get();
        $priorities = Priority::where('rutEmpresa', auth()->user()->rutEmpresa)->get();
        $teams = Team::where('rutEmpresa', auth()->user()->rutEmpresa)->get();
        $requerimientosAnidadosLista = Anidado::where('idRequerimientoBase', $requerimiento->id)->get();
        $requerimientos = Requerimiento::where('rutEmpresa', auth()->user()->rutEmpresa)->get();
        $requerimientosAnidados = [];
        foreach ($requerimientosAnidadosLista as $requerimiento1)
        {
            foreach ($requerimientos as $requerimientos1) 
            {
                if ($requerimientos1->id == $requerimiento1->idRequerimientoAnexo) 
                {
                    $requerimientosAnidados[] = $requerimientos1;
                }
            }
        }
        define("FECHACIERRE", "$requerimiento->fechaCierre");
        define("FECHASOLICITUD", "$requerimiento->fechaSolicitud");
        define("FECHAREALCIERRE", "$requerimiento->fechaRealCierre");
        $fechaCierre = new DateTime(FECHACIERRE);
        $restantes = 0;
            
        if(empty($resolutor2)){
            $resolutor2 = 0;
        }
            
        $log_requerimiento = LogRequerimientos::where('idRequerimiento', $requerimiento->id)->get();
            
        if(count($log_requerimiento)==0){
            $ver_log = false;
        } else {
            $ver_log = true;
        }
        
        return view('Requerimientos.show', compact('user','requerimiento', 'resolutors',
                    'priorities', 'avances', 'teams', 'fechaCierre', 'requerimientosAnidados',
                    'tareas', 'requerimientos', 'solicitante', 'resolutor','resolutor2',
                    'resolutores', 'lider', 'res', 'id2', 'ver_log'));        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Requerimiento  $requerimiento
     * @return \Illuminate\Http\Response
     */
    public function edit(Requerimiento $requerimiento)
    {

        $user = DB::table('usuarios')->where('idUser', auth()->user()->id)->get();
        $lider = 0;
        if ($user[0]->nombre == "resolutor") {
            $resolutor = Resolutor::where('idUser', $user[0]->idUser)->first('lider');
            $lider = $resolutor->lider;           
        }        
        $fechita = str_split($requerimiento->fechaCierre);
        $fechota = [];
        for ($i=0; $i < 10; $i++) { 
            $b = strtoupper($fechita[$i]);
            array_push($fechota, $b);
        }
        $cierre = implode("", $fechota);

        $fechita = str_split($requerimiento->fechaSolicitud);
        $fechota = [];
        for ($i=0; $i < 10; $i++) { 
            $b = strtoupper($fechita[$i]);
            array_push($fechota, $b);
        }
        $solicitud = implode("", $fechota);

        $solicitantes = Solicitante::where('rutEmpresa', auth()->user()->rutEmpresa)->orderBy('nombreSolicitante')->get();
        
        $solicitanteEspecifico = Solicitante::where([
            ['rutEmpresa', auth()->user()->rutEmpresa],
            ['id', $requerimiento->idSolicitante],
        ])->get();
        
        $prioridadEspecifica = Priority::where([
            ['rutEmpresa', auth()->user()->rutEmpresa],
            ['id', $requerimiento->idPrioridad],
        ])->get();
        
        $resolutorEspecifico = Resolutor::where([
            ['rutEmpresa', auth()->user()->rutEmpresa],
            ['id', $requerimiento->resolutor],
        ])->get();
        
        $id_resolutor = $resolutorEspecifico[0]['id'];
        
        $priorities = Priority::where('rutEmpresa', auth()->user()->rutEmpresa)->get();
        $resolutors = Resolutor::where('rutEmpresa', auth()->user()->rutEmpresa)->get();
        $fechaCierre = new DateTime($requerimiento->fechaCierre);
        if($user[0]->nombre == "resolutor")
        {
            $resolutor = Resolutor::where('idUser', $user[0]->idUser)->first();
            $teams = Team::where('id', $resolutor->idTeam)->get();
        } else
        {    
            $teams = Team::where('rutEmpresa', auth()->user()->rutEmpresa)->get();
        }
        
        return view('Requerimientos.edit', compact('requerimiento', 'solicitantes', 'priorities', 'resolutors', 'fechaCierre', 'cierre', 'solicitud', 'solicitanteEspecifico', 'prioridadEspecifica', 'resolutorEspecifico', 'user', 'lider', 'teams', 'id_resolutor'));        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Requerimiento  $requerimiento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Requerimiento $requerimiento)
    {
        $data = request()->validate([
            'textoRequerimiento' => 'nullable',
            'idSolicitante' => 'nullable',
            'idPrioridad' => 'nullable',
            'resolutor' => 'required',
            'fechaCierre' => 'nullable',
            'textAvance' => 'nullable',            
            'fechaSolicitud' => 'nullable',
        ]);
        
        $data['textoRequerimiento'] = preg_replace("/[\r\n|\n|\r|\t]+/", " ", $data['textoRequerimiento']);
        $data['textAvance'] = preg_replace("/[\r\n|\n|\r|\t]+/", " ", $data['textAvance']);

        $data['idEmpresa'] = auth()->user()->rutEmpresa;

        $user = User::where([
            ['name', auth()->user()->name],
            ['rutEmpresa', auth()->user()->rutEmpresa],
        ])->get();

        if ($data['textoRequerimiento'] != $requerimiento->textoRequerimiento) {
            LogRequerimientos::create([
                'idRequerimiento' => $requerimiento->id,
                'idUsuario' => $user[0]->id,
                'tipo' => 'edicion',
                'campo' => 'texto requerimiento',
            ]);             
        }

        if ($data['idSolicitante'] != $requerimiento->idSolicitante) {
            LogRequerimientos::create([
                'idRequerimiento' => $requerimiento->id,
                'idUsuario' => $user[0]->id,
                'tipo' => 'edicion',
                'campo' => 'solicitante',
            ]);              
        }

        if ($data['idPrioridad'] != $requerimiento->idPrioridad) {
            LogRequerimientos::create([
                'idRequerimiento' => $requerimiento->id,
                'idUsuario' => $user[0]->id,
                'tipo' => 'edicion',
                'campo' => 'prioridad',
            ]);             
        }

        if ($data['resolutor'] != $requerimiento->resolutor) {
            LogRequerimientos::create([
                'idRequerimiento' => $requerimiento->id,
                'idUsuario' => $user[0]->id,
                'tipo' => 'edicion',
                'campo' => 'resolutor',
            ]);
            
            $id_equipo_resolutor = DB::table('requerimientos_equipos')
                    ->where('resolutor', $requerimiento->resolutor)
                    ->where('id', $requerimiento->id)
                    ->first('idEquipo');
           
            
            $id_equipo_resolutor_actual = DB::table('requerimientos_equipos')
                    ->where('resolutor', $data['resolutor'])
                    ->first('idEquipo');
            
            if($id_equipo_resolutor->idEquipo != $id_equipo_resolutor_actual->idEquipo){
                $equipo_resolutor_actual = Team::where('id', 
                        $id_equipo_resolutor_actual->idEquipo)
                        ->first();
                
                $ultimo_requerimiento = DB::table('requerimientos_equipos')
                        ->where('idEquipo', $equipo_resolutor_actual->id)
                        ->orderby('created_at','DESC')
                        ->first('id2');
                
                $texto_ultimo_req = $ultimo_requerimiento->id2;
                $primera_parte = substr($requerimiento->id2, 0,3);
                $segunda_parte = substr($texto_ultimo_req, 3,-3);
                $numero_req = (int) substr($texto_ultimo_req, -3);
                $numero_req = $numero_req+1;
                if($numero_req<10){
                    $nuevo_id = $primera_parte.$segunda_parte."00".strval($numero_req);
                } elseif($numero_req<100){
                    $nuevo_id = $primera_parte.$segunda_parte."0".strval($numero_req);
                } else {
                    $nuevo_id = $primera_parte.$segunda_parte.strval($numero_req);
                }
                $data['id2'] = $nuevo_id;  
            }
        } 

        $fechita = str_split($requerimiento->fechaCierre);
        $fechota = [];
        for ($i=0; $i < 10; $i++) { 
            $b = strtoupper($fechita[$i]);
            array_push($fechota, $b);
        }
        $cierre = implode("", $fechota);                
        
        if(empty($data['fechaCierre']) == false){
            if ($data['fechaCierre'] != $cierre) {
                LogRequerimientos::create([
                    'idRequerimiento' => $requerimiento->id,
                    'idUsuario' => $user[0]->id,
                    'tipo' => 'edicion',
                    'campo' => 'fecha cierre solicitud',
                ]);
            }
        }  

        $fechita = str_split($requerimiento->fechaSolicitud);
        $fechota = [];
        for ($i=0; $i < 10; $i++) { 
            $b = strtoupper($fechita[$i]);
            array_push($fechota, $b);
        }
        $solicitud = implode("", $fechota);        

        if ($data['fechaSolicitud'] != $solicitud) {
            LogRequerimientos::create([
                'idRequerimiento' => $requerimiento->id,
                'idUsuario' => $user[0]->id,
                'tipo' => 'edicion',
                'campo' => 'fecha solicitud',
            ]);
        }
        
        $data['fechaRealCierre'] = null;
              
        $requerimiento->update($data);

        if ($data['textAvance'] != "") {

                Avance::create([
                    'textAvance' => $data['textAvance'],
                    'fechaAvance' => Carbon::now(),
                    'idRequerimiento' => $requerimiento->id
                ]);            
            
        }
        return redirect('requerimientos');         
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Requerimiento  $requerimiento
     * @return \Illuminate\Http\Response
     */
    public function destroy(Requerimiento $requerimiento)
    {
        $user = User::where([
            ['name', auth()->user()->name],
            ['rutEmpresa', auth()->user()->rutEmpresa],
        ])->get();        
        LogRequerimientos::create([
            'idRequerimiento' => $requerimiento->id,
            'idUsuario' => $user[0]->id,
            'tipo' => 'eliminacion',
            'campo' => '',
        ]);       
        $data = [
            'estado' => 0,
        ];
        DB::table('requerimientos')->where('id', $requerimiento->id)->update($data);

        return redirect('requerimientos');   
    }

    public function actualizar(Requerimiento $requerimiento)
    {
        return view('Requerimientos.actualizar', compact('requerimiento'));        
    }

    public function save(Request $request, Requerimiento $requerimiento)
    {
        if ($request->fechaRealCierre != "") {
            $cambios=$requerimiento->numeroCambios;
            if ($cambios == null) {
                $cambios = 1;
                $request->merge(['numeroCambios' => $cambios]);
                $data = request()->validate([
                    'fechaRealCierre' => 'nullable',
                    'numeroCambios' => 'nullable',
                    'porcentajeEjecutado' => 'nullable',
                    'cierre' => 'nullable'
                ]);


                $requerimiento->update($data);
                return redirect()->route('Requerimientos.show', ['requerimiento' => $requerimiento]);                 
            } else {
                $cambios +=1;
                $request->merge(['numeroCambios' => $cambios]);                
                $data = request()->validate([
                    'fechaRealCierre' => 'nullable',
                    'numeroCambios' => 'nullable',
                    'porcentajeEjecutado' => 'nullable',
                    'cierre' => 'nullable'
                ]);

                $user = User::where([
                    ['name', auth()->user()->name],
                    ['rutEmpresa', auth()->user()->rutEmpresa],
                ])->get();  

                $requerimiento->update($data);
                return redirect()->route('Requerimientos.show', ['requerimiento' => $requerimiento]);                
            }
        }
        else 
        {
            
            $user = User::where([
                ['name', auth()->user()->name],
                ['rutEmpresa', auth()->user()->rutEmpresa],
            ])->get();  

            $data = request()->validate([
                'fechaRealCierre' => 'nullable',
                'numeroCambios' => 'nullable',
                'porcentajeEjecutado' => 'nullable',
                'cierre' => 'nullable'
            ]);
            $requerimiento->update($data);
            return redirect()->route('Requerimientos.show', ['requerimiento' => $requerimiento]);
        }            
    }

    public function terminado(Requerimiento $requerimiento)
    {
        $user = DB::table('usuarios')->where([
            ['idUser', auth()->user()->id],
        ])->get();
        $lider = 0;
        if ($user[0]->nombre == "resolutor") {
            $resolutor = Resolutor::where('idUser', $user[0]->idUser)->first('lider');
            $lider = $resolutor->lider;           
        }
        
        if($requerimiento->fechaRealCierre != null){
            $fecha = new DateTime($requerimiento->fechaRealCierre);
        } else {
            $fecha = new DateTime($requerimiento->fechaCierre);
        }        
        return view('Requerimientos.terminado', compact('requerimiento', 'user', 'lider','fecha'));        
    } 

    public function guardar(Request $request, Requerimiento $requerimiento)
    {
        
        $user = DB::table('usuarios')->where([
            ['idUser', auth()->user()->id],
        ])->get();
        
        $hoy = new DateTime();
        $data = request()->validate([
            'cierre'=>'required',
            'fechaRealCierre' => 'nullable'],
            ['cierre.required' => 'El texto de cierre es obligatorio']);
       
        if (empty($data['fechaRealCierre'])) {
            $data = [
                'estado' => 1,
                'porcentajeEjecutado' => 100,
                'cierre' => $data['cierre'],
                'aprobacion' => 4,
                'fechaLiquidacion' => $hoy,
            ];           
        } else {

            $realData = new DateTime($data['fechaRealCierre']);
            if($requerimiento->fechaRealCierre != null){
                $realReq = new DateTime($requerimiento->fechaRealCierre);
            } else {
                $realReq = new DateTime($requerimiento->fechaCierre);
            }
            if($realData != $realReq){
                $data = [
                    'estado' => 1,
                    'porcentajeEjecutado' => 100,
                    'cierre' => $data['cierre'],
                    'fechaRealCierre' => $data['fechaRealCierre'],
                    'aprobacion' => 4,
                    'fechaLiquidacion' => $hoy,
                ];
                
                LogRequerimientos::create([
                    'idRequerimiento' => $requerimiento->id,
                    'idUsuario' => $user[0]->idUser,
                    'tipo' => 'edicion',
                    'campo' => 'fecha cierre resolutor',
                ]);
            } else {
                $data = [
                    'estado' => 1,
                    'porcentajeEjecutado' => 100,
                    'cierre' => $data['cierre'],
                    'aprobacion' => 4,
                    'fechaLiquidacion' => $hoy,
                ];                
            }
        }
        $variable =$requerimiento->update($data);
        
        $tareas = Tarea::where('idRequerimiento', $requerimiento->id)->get();
        
        foreach ($tareas as $tarea){
            $data = [
                'estado' => 2];   
            $tarea->update($data);            
        }
        
        LogRequerimientos::create([
            'idRequerimiento' => $requerimiento->id,
            'idUsuario' => $user[0]->idUser,
            'tipo' => 'terminar',
            'campo' => '',
        ]);
        
        $resolutor = Resolutor::where('id', $requerimiento->resolutor)->first();
        $parametros = Parametros::where('rutEmpresa', auth()->user()->rutEmpresa)->first();
        if($resolutor->email == $parametros->emailSupervisor){
            self::autorizar($requerimiento);
        } else{
            $solicitante = Solicitante::where('id', $requerimiento->idSolicitante)->first();
            $usuario_solicitante = User::where('name', $solicitante->nombreSolicitante)->first();
            $parametros = Parametros::where('rutEmpresa', auth()->user()->rutEmpresa)->first();
            $obj = new \stdClass();
            $obj->idReq = $requerimiento->id2;
            $obj->id = $requerimiento->id;
            $obj->sol = $requerimiento->textoRequerimiento;
            $obj->nombre = $resolutor->nombreResolutor;
            $obj->solicitante = $solicitante->nombreSolicitante;
            $email = $usuario_solicitante->email;

            Notification::route('mail', $email)->notify(new FinalizadoNotifi($obj));            
        }
   
        return redirect('requerimientos'); 
    }

        public function cerrar_anidado(Requerimiento $requerimiento)
    {
        $hoy = new DateTime();
        $cierre = "requerimiento cerrado como anidado";
        
        $data = [
            'estado' => 2,
            'aprobacion' => 1,
            'porcentajeEjecutado' => 100,
            'cierre' => $cierre,
            'fechaLiquidacion' => $hoy,
            'fecha_aprobacion_supervisor' => $hoy,
        ];
        
        $requerimiento->update($data);
       
    }
    
    public function activar(Requerimiento $requerimiento){
        $data = [
            'estado' => 1,
            'porcentajeEjecutado' => 80,
            'cierre' => "",
            'aprobacion' => 3,
        ];
        $requerimiento->update($data);
        
        $user = User::where([
            ['name', auth()->user()->name],
            ['rutEmpresa', auth()->user()->rutEmpresa],
        ])->get();
        
        LogRequerimientos::create([
            'idRequerimiento' => $requerimiento->id,
            'idUsuario' => $user[0]->id,
            'tipo' => 'activar',
        ]);        
        
        return redirect('requerimientos');   
    }

    public function autorizar(Requerimiento $requerimiento)
    {
        $hoy = new DateTime();
        $data = [
            'estado' => 2,
            'aprobacion' => 1,
            'fecha_aprobacion_supervisor' => $hoy,
        ];
        $requerimiento->update($data);
        
        $user = User::where([
            ['name', auth()->user()->name],
            ['rutEmpresa', auth()->user()->rutEmpresa],
        ])->get();
        
        LogRequerimientos::create([
            'idRequerimiento' => $requerimiento->id,
            'idUsuario' => $user[0]->id,
            'tipo' => 'autorizar',
        ]);        
        
        $anidados = Anidado::where('idRequerimientoBase', $requerimiento->id)->get();
        
        $requerimientos = [];
        foreach ($anidados as $anidado)
        {
            $req = Requerimiento::where('id', $anidado->idRequerimientoAnexo)->first();
            self::cerrar_anidado($req);
        }
        
        return redirect('requerimientos?state=6&valorN=');
    }

    public function aceptar($requerimiento) {
        $req = Requerimiento::where('id', $requerimiento)->first();
        self::autorizar($req);
        
        return redirect('requerimientos?state=6&valorN=');
        return back()->with('msj', 'Requerimiento autorizado');
    }

    public function rechazar($requerimiento) {
        $estado = 6;
        $user = User::where([
            ['name', auth()->user()->name],
            ['rutEmpresa', auth()->user()->rutEmpresa],
        ])->get();
        $lider = 0;
        if ($user[0]->nombre == "resolutor") {
            $resolutor = Resolutor::where('idUser', $user[0]->idUser)->first('lider');
            $lider = $resolutor->lider;           
        }   
        return view('Requerimientos.rechazar', compact('requerimiento', 'user', 'lider','estado'));
    }

    public function RequerimientoRechazado(Request $request) {
        $req = Requerimiento::where('id', $request->requerimiento)->first();
        $data = [
            'estado' => 1,
            'aprobacion' => 3,
            'rechazo' => $request->rechazo,
            'porcentajeEjecutado' => 80,
            'fechaRealCierre' => NULL,
        ];
        $req->update($data);
        
        $user = User::where([
            ['name', auth()->user()->name],
            ['rutEmpresa', auth()->user()->rutEmpresa],
        ])->get();        
        
        LogRequerimientos::create([
            'idRequerimiento' => $req->id,
            'idUsuario' => $user[0]->id,
            'tipo' => 'rechazar',
        ]);        

        $resolutor = Resolutor::where('id', $req->resolutor)->first();
        $solicitante = Solicitante::where('id', $req->idSolicitante)->first();

        $obj = new \stdClass();
        $obj->idReq = $req->id2;
        $obj->id = $req->id;
        $obj->sol = $req->textoRequerimiento;
        $obj->nombre = $resolutor->nombreResolutor;
        $obj->solicitante = $solicitante->nombreSolicitante;
        $obj->rechazo = $req->rechazo;

        $recep = $resolutor->email;

        Notification::route('mail', $recep)->notify(new RechazadoNotifi($obj)); 
        /* se registra el rechazo del requerimiento */
        
        if ($request->fActivo == "6") {
            if ($request->fSolicitante!="" && $request->fSolicitante!=null && $request->fSolicitante!="null")
                return redirect('requerimientos?state=6'.$request->fValor.'&solicitante='.$request->fSolicitante);
            else
                return redirect('requerimientos?state=6&valorN=');
        }
        
        return redirect('requerimientos');
    }

    public function ver_log(Requerimiento $requerimiento){
        $user = DB::table('usuarios')->where('idUser', auth()->user()->id)->get();
        $id2 = substr($requerimiento->id2,0,3);
        
        //Desde ac� deber� borrar
        $log_requerimiento = LogRequerimientos::where('idRequerimiento', $requerimiento->id)->get();
        $resolutors = Resolutor::where('rutEmpresa', auth()->user()->rutEmpresa)->get();
        // Hasta ac� deber� borrar
        
        //dd($log_requerimiento);
        if(count($log_requerimiento)==1){
            $elementos_log=[];
            $usuarios_log = [];
            foreach($log_requerimiento as $log)
            {
                if($log->tipo == "creacion")
                {
                    //dd($log);
                    $creador = DB::table('usuarios')->where('idUser', $log->idUsuario)->first();
                    $fecha_creacion = new DateTime($log->created_at);
                    $fecha_creacion = $fecha_creacion->format('d-m-Y');
                } else {
                    $creador = [];
                    $creador['name'] = " ";
                    $creador = (object)$creador;
                    $fecha_creacion = "";
                    $elementos_log = [];
                    $usuarios_log = [];
                }

                if($log->tipo == "edicion")
                {
                    $usuario = DB::table('usuarios')->where('idUser', $log->idUsuario)->first();
                    $usuarios_log [] = $usuario;
                    $elementos_log[] = $log;
                    
                }                

                if($log->tipo == "terminar")
                {
                    $usuario = DB::table('usuarios')->where('idUser', $log->idUsuario)->first();
                    $usuarios_log [] = $usuario;
                    $elementos_log[] = $log;                    
                }                
            }            
        } elseif(count($log_requerimiento)>1){
            $elementos_log = [];
            $usuarios_log = [];
            foreach ($log_requerimiento as $log)
            {

                if($log->tipo == "creacion")
                {
                  
                    $creador = DB::table('usuarios')->where('idUser', $log->idUsuario)->first();
                    $fecha_creacion = new DateTime($log->created_at);
                    $fecha_creacion = $fecha_creacion->format('d-m-Y');
                }
                
                if($log->tipo == "edicion")
                {
                    $usuario = DB::table('usuarios')->where('idUser', $log->idUsuario)->first();
                    $usuarios_log [] = $usuario;
                    $elementos_log[] = $log;
                    
                }
                
                if($log->tipo == "terminar")
                {
                    $usuario = DB::table('usuarios')->where('idUser', $log->idUsuario)->first();
                    $usuarios_log [] = $usuario;
                    $elementos_log[] = $log;                    
                }
                
                if($log->tipo == "autorizar")
                {
                    $usuario = DB::table('usuarios')->where('idUser', $log->idUsuario)->first();
                    $usuarios_log [] = $usuario;
                    $elementos_log[] = $log;                    
                }

                if($log->tipo == "rechazar")
                {
                    $usuario = DB::table('usuarios')->where('idUser', $log->idUsuario)->first();
                    $usuarios_log [] = $usuario;
                    $elementos_log[] = $log;                    
                }                 
            }
        }
        
        if(empty($creador)){
            $creador = [];
            $creador['name'] = " ";
            
            $creador = (object)$creador;
        }
        
        
        if(empty($fecha_creacion)){
            $fecha_creacion = " ";
        }
        return view('Requerimientos.log', compact('user', 'id2', 'requerimiento',
                    'resolutors', 'creador', 'fecha_creacion', 'elementos_log', 'usuarios_log')); 
    }

    public function mail_info(Request $request, Requerimiento $requerimiento)
    {
        $emails = $request->input_email;
        $emails = str_replace(" ", "", $emails);
        $emails_array = explode(",", $emails);
        
        foreach ($emails_array as $email) {
            try {
                $resolutor = Resolutor::where('id', $requerimiento->resolutor)->first();
                $solicitante = Solicitante::where('id', $requerimiento->idSolicitante)->first();
                $avance = Avance::where('idRequerimiento', $requerimiento->id)->
                        orderby('created_at','DESC')->first();

                $obj = new \stdClass();
                $obj->idReq = $requerimiento->id2;
                $obj->id = $requerimiento->id;
                $obj->sol = $requerimiento->textoRequerimiento;
                $obj->avance = $avance->textAvance;
                $obj->porcentaje = $requerimiento->porcentajeEjecutado;

                $recep = $email;

                Notification::route('mail', $recep)->notify(new Mail_info($obj));                
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }
       
        return back()->with('msj', 'Se realiz� el env�o de email.');
    }

    public function recalcular(){
        $requerimientos = Requerimiento::where([
            ['estado', '=', 1],
            ['rutEmpresa', '=', auth()->user()->rutEmpresa],            
        ])->get();
        
        $data = request()->validate([
            'textoRequerimiento' => 'nullable',
            'idSolicitante' => 'nullable',
            'idPrioridad' => 'nullable',
            'resolutor' => 'nullable',
            'fechaCierre' => 'nullable',
            'textAvance' => 'nullable',            
            'fechaSolicitud' => 'nullable',
        ]);        
        foreach ($requerimientos as $requerimiento){
            $variable = new DateTime($requerimiento['fechaCierre']);
            $intervalo = new DateInterval('PT23H59M59S');
            $variable->add($intervalo);
            $data['fechaCierre'] = $variable;
            if($requerimiento['fechaRealCierre'] != "" and $requerimiento['fechaRealCierre'] != null){
                $variable = new DateTime($requerimiento['fechaRealCierre']);
                $intervalo = new DateInterval('PT23H59M59S');
                $variable->add($intervalo);
                $data['fechaRealCierre'] = $variable;
            }
            $requerimiento->update($data);
        }
    }

    public function for_date(Request $request){
        //dd($request);
        $tipo = $request->tipo;
  
        $resolutors = Resolutor::where('rutEmpresa', auth()->user()->rutEmpresa)->get();
        $solicitantes = Solicitante::where('rutEmpresa', auth()->user()->rutEmpresa)->orderBy('nombreSolicitante')->get();
        
        $anidados = Anidado::all();

        if (isset($request->tipo)) {
            $request->session()->put('tipo', $request->tipo);
        } else {
            $request->session()->put('tipo', "1");
            $tipo = 1;
        }
        //dd($tipo);
        
        $user = DB::table('usuarios')->where('idUser', auth()->user()->id)->first();
        $res = Resolutor::where('idUser', $user->idUser)->first();
        $hoy = new DateTime(date('Y-m-d'));
        $un_dia = $hoy->add(new DateInterval('P1D'));
        $hoy = new DateTime(date('Y-m-d'));
        $dos_dia = $hoy->add(new DateInterval('P2D'));
        $hoy = new DateTime(date('Y-m-d'));
        $tres_dia = $hoy->add(new DateInterval('P3D'));
        $hoy = new DateTime(date('Y-m-d'));
        $siet_dia = $hoy->add(new DateInterval('P7D'));
        $hoy = new DateTime(date('Y-m-d'));
        $diez_dia = $hoy->add(new DateInterval('P10D'));
        $hoy = new DateTime(date('Y-m-d'));
        
        switch ($tipo) {
            case '1':
                $req = DB::table('requerimientos_equipos')->where([
                            ['estado', 1],
                            ['aprobacion', '=', '3'],
                            ['rutEmpresa', auth()->user()->rutEmpresa],
                            ['resolutor', $res->id],
                        ])->get();
                
                //dd($req);
                
                $requerimientos = [];

                if (count($req) != 0) {
                    foreach ($req as $requerimiento) {
                        $requerimiento = (array) $requerimiento;
                        $requerimiento ['tipo'] = "requerimiento";
                        $requerimient [] = $requerimiento;
                        $tareas = Tarea::where([
                                    ['idRequerimiento', $requerimiento['id']],
                                    ['estado', 1]
                                ])->get();
                        if (count($tareas) != 0) {
                            foreach ($tareas as $tarea) {
                                $tarea ['tipo'] = "tarea";
                                $requerimient [] = $tarea;
                            }
                        }
                    }
                }
                
                foreach ($requerimient as $requerimiento) {
                    foreach ($anidados as $anidado) {
                        if ($anidado->idRequerimientoAnexo == $requerimiento['id']) {
                            $estado = false;
                        }
                    }
                    if ($requerimiento['fechaCierre'] == "9999-12-31 00:00:00") {
                        $requerimiento ['status'] = 1;
                    } else 
                    {
                        if($requerimiento['fechaRealCierre'] != ""){
                            $cierre = new Datetime($requerimiento['fechaRealCierre']);
                        } else 
                        {
                            $cierre = new DateTime($requerimiento['fechaCierre']);
                        }
                                    
                        if ($cierre->getTimestamp()<$hoy->getTimestamp()) {
                            $requerimiento ['status'] = 3;
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
                            } else {
                                $requerimiento ['status'] = 1;
                            }
                            $variable = 0;
                            unset($hoy);
                            $hoy = new DateTime();                           
                        }                        
                    }
                    if ($requerimiento['fechaRealCierre'] != "") {
                        $cierre_req = new DateTime($requerimiento['fechaRealCierre']);
                        if ($cierre_req->getTimestamp() > $hoy->getTimestamp() and
                                $cierre_req->getTimestamp() < $un_dia->getTimestamp()){
                            $requerimiento = (object) $requerimiento;
                            $requerimientos[]=$requerimiento;
                        }   
                    } else {
                        $cierre_req = new DateTime($requerimiento['fechaCierre']);
                        if ($cierre_req->getTimestamp() > $hoy->getTimestamp() and
                                $cierre_req->getTimestamp() < $un_dia->getTimestamp()){
                            $requerimiento = (object) $requerimiento;
                            $requerimientos[]=$requerimiento;
                        }                        
                    }
                }
                
                $valor = 1;
                if ($request->session()->get('tipo') == 1) {
                    $valor = 1;
                } else {
                    $valor = 0;
                }
                $lider = 0;
                if ($user->nombre == "resolutor") {
                    $resolutor = Resolutor::where('idUser', $user[0]->idUser)->first('lider');
                    $lider = $resolutor->lider;
                }
                //dd($requerimientos);
                $requerimientos = (object)$requerimientos; 
            break;
            case '2':
                $req = DB::table('requerimientos_equipos')->where([
                            ['estado', 1],
                            ['aprobacion', '=', '3'],
                            ['rutEmpresa', auth()->user()->rutEmpresa],
                            ['resolutor', $res->id],
                        ])->get();
                
                //dd($req);
                
                $requerimientos = [];

                if (count($req) != 0) {
                    foreach ($req as $requerimiento) {
                        $requerimiento = (array) $requerimiento;
                        $requerimiento ['tipo'] = "requerimiento";
                        $requerimient [] = $requerimiento;
                        $tareas = Tarea::where([
                                    ['idRequerimiento', $requerimiento['id']],
                                    ['estado', 1]
                                ])->get();
                        if (count($tareas) != 0) {
                            foreach ($tareas as $tarea) {
                                $tarea ['tipo'] = "tarea";
                                $requerimient [] = $tarea;
                            }
                        }
                    }
                }
                
                foreach ($requerimient as $requerimiento) {
                    foreach ($anidados as $anidado) {
                        if ($anidado->idRequerimientoAnexo == $requerimiento['id']) {
                            $estado = false;
                        }
                    }
                    if ($requerimiento['fechaCierre'] == "9999-12-31 00:00:00") {
                        $requerimiento ['status'] = 1;
                    } else 
                    {
                        if($requerimiento['fechaRealCierre'] != ""){
                            $cierre = new Datetime($requerimiento['fechaRealCierre']);
                        } else 
                        {
                            $cierre = new DateTime($requerimiento['fechaCierre']);
                        }
                                    
                        if ($cierre->getTimestamp()<$hoy->getTimestamp()) {
                            $requerimiento ['status'] = 3;
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
                            } else {
                                $requerimiento ['status'] = 1;
                            }
                            $variable = 0;
                            unset($hoy);
                            $hoy = new DateTime();                           
                        }                        
                    }                    
                    if ($requerimiento['fechaRealCierre'] != "") {
                        $cierre_req = new DateTime($requerimiento['fechaRealCierre']);
                        if ($cierre_req->getTimestamp() > $hoy->getTimestamp() and
                                $cierre_req->getTimestamp() < $dos_dia->getTimestamp()){
                            $requerimiento = (object) $requerimiento;
                            $requerimientos[]=$requerimiento;
                        }   
                    } else {
                        $cierre_req = new DateTime($requerimiento['fechaCierre']);
                        if ($cierre_req->getTimestamp() > $hoy->getTimestamp() and
                                $cierre_req->getTimestamp() < $dos_dia->getTimestamp()){
                            $requerimiento = (object) $requerimiento;
                            $requerimientos[]=$requerimiento;
                        }                        
                    }
                }
                
                $valor = 1;
                if ($request->session()->get('tipo') == 1) {
                    $valor = 1;
                } else {
                    $valor = 0;
                }
                $lider = 0;
                if ($user->nombre == "resolutor") {
                    $resolutor = Resolutor::where('idUser', $user[0]->idUser)->first('lider');
                    $lider = $resolutor->lider;
                }
                
                $requerimientos = (object)$requerimientos;
            break;
            case '3':
                $req = DB::table('requerimientos_equipos')->where([
                            ['estado', 1],
                            ['aprobacion', '=', '3'],
                            ['rutEmpresa', auth()->user()->rutEmpresa],
                            ['resolutor', $res->id],
                        ])->get();
                
                //dd($req);
                
                $requerimientos = [];

                if (count($req) != 0) {
                    foreach ($req as $requerimiento) {
                        $requerimiento = (array) $requerimiento;
                        $requerimiento ['tipo'] = "requerimiento";
                        $requerimient [] = $requerimiento;
                        $tareas = Tarea::where([
                                    ['idRequerimiento', $requerimiento['id']],
                                    ['estado', 1]
                                ])->get();
                        if (count($tareas) != 0) {
                            foreach ($tareas as $tarea) {
                                $tarea ['tipo'] = "tarea";
                                $requerimient [] = $tarea;
                            }
                        }
                    }
                }
                
                foreach ($requerimient as $requerimiento) {
                    foreach ($anidados as $anidado) {
                        if ($anidado->idRequerimientoAnexo == $requerimiento['id']) {
                            $estado = false;
                        }
                    }
                    if ($requerimiento['fechaCierre'] == "9999-12-31 00:00:00") {
                        $requerimiento ['status'] = 1;
                    } else 
                    {
                        if($requerimiento['fechaRealCierre'] != ""){
                            $cierre = new Datetime($requerimiento['fechaRealCierre']);
                        } else 
                        {
                            $cierre = new DateTime($requerimiento['fechaCierre']);
                        }
                                    
                        if ($cierre->getTimestamp()<$hoy->getTimestamp()) {
                            $requerimiento ['status'] = 3;
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
                            } else {
                                $requerimiento ['status'] = 1;
                            }
                            $variable = 0;
                            unset($hoy);
                            $hoy = new DateTime();                           
                        }                        
                    }                    
                    if ($requerimiento['fechaRealCierre'] != "") {
                        $cierre_req = new DateTime($requerimiento['fechaRealCierre']);
                        if ($cierre_req->getTimestamp() > $hoy->getTimestamp() and
                                $cierre_req->getTimestamp() < $tres_dia->getTimestamp()){
                            $requerimiento = (object) $requerimiento;
                            $requerimientos[]=$requerimiento;
                        }   
                    } else {
                        $cierre_req = new DateTime($requerimiento['fechaCierre']);
                        if ($cierre_req->getTimestamp() > $hoy->getTimestamp() and
                                $cierre_req->getTimestamp() < $tres_dia->getTimestamp()){
                            $requerimiento = (object) $requerimiento;
                            $requerimientos[]=$requerimiento;
                        }                        
                    }
                }
                
                $valor = 1;
                if ($request->session()->get('tipo') == 1) {
                    $valor = 1;
                } else {
                    $valor = 0;
                }
                $lider = 0;
                if ($user->nombre == "resolutor") {
                    $resolutor = Resolutor::where('idUser', $user[0]->idUser)->first('lider');
                    $lider = $resolutor->lider;
                }
                
                $requerimientos = (object)$requerimientos;
            break;
            case '4':
                $req = DB::table('requerimientos_equipos')->where([
                            ['estado', 1],
                            ['aprobacion', '=', '3'],
                            ['rutEmpresa', auth()->user()->rutEmpresa],
                            ['resolutor', $res->id],
                        ])->get();
                
                //dd($req);
                
                $requerimientos = [];

                if (count($req) != 0) {
                    foreach ($req as $requerimiento) {
                        $requerimiento = (array) $requerimiento;
                        $requerimiento ['tipo'] = "requerimiento";
                        $requerimient [] = $requerimiento;
                        $tareas = Tarea::where([
                                    ['idRequerimiento', $requerimiento['id']],
                                    ['estado', 1]
                                ])->get();
                        if (count($tareas) != 0) {
                            foreach ($tareas as $tarea) {
                                $tarea ['tipo'] = "tarea";
                                $requerimient [] = $tarea;
                            }
                        }
                    }
                }
                
                foreach ($requerimient as $requerimiento) {
                    foreach ($anidados as $anidado) {
                        if ($anidado->idRequerimientoAnexo == $requerimiento['id']) {
                            $estado = false;
                        }
                    }
                    if ($requerimiento['fechaCierre'] == "9999-12-31 00:00:00") {
                        $requerimiento ['status'] = 1;
                    } else 
                    {
                        if($requerimiento['fechaRealCierre'] != ""){
                            $cierre = new Datetime($requerimiento['fechaRealCierre']);
                        } else 
                        {
                            $cierre = new DateTime($requerimiento['fechaCierre']);
                        }
                                    
                        if ($cierre->getTimestamp()<$hoy->getTimestamp()) {
                            $requerimiento ['status'] = 3;
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
                            } else {
                                $requerimiento ['status'] = 1;
                            }
                            $variable = 0;
                            unset($hoy);
                            $hoy = new DateTime();                           
                        }                        
                    }                    
                    if ($requerimiento['fechaRealCierre'] != "") {
                        $cierre_req = new DateTime($requerimiento['fechaRealCierre']);
                        if ($cierre_req->getTimestamp() > $hoy->getTimestamp() and
                            $cierre_req->getTimestamp() < $siet_dia->getTimestamp()){
                            $requerimiento = (object) $requerimiento;
                            $requerimientos[]=$requerimiento;
                        }   
                    } else {
                        $cierre_req = new DateTime($requerimiento['fechaCierre']);
                        if ($cierre_req->getTimestamp() > $hoy->getTimestamp() and
                            $cierre_req->getTimestamp() < $siet_dia->getTimestamp()){
                            $requerimiento = (object) $requerimiento;
                            $requerimientos[]=$requerimiento;
                        }                        
                    }
                }
                
                $valor = 1;
                if ($request->session()->get('tipo') == 1) {
                    $valor = 1;
                } else {
                    $valor = 0;
                }
                $lider = 0;
                if ($user->nombre == "resolutor") {
                    $resolutor = Resolutor::where('idUser', $user[0]->idUser)->first('lider');
                    $lider = $resolutor->lider;
                }
                
                $requerimientos = (object)$requerimientos;
            break;
            case '5':
                $req = DB::table('requerimientos_equipos')->where([
                            ['estado', 1],
                            ['aprobacion', '=', '3'],
                            ['rutEmpresa', auth()->user()->rutEmpresa],
                            ['resolutor', $res->id],
                        ])->get();
                
                //dd($req);
                
                $requerimientos = [];

                if (count($req) != 0) {
                    foreach ($req as $requerimiento) {
                        $requerimiento = (array) $requerimiento;
                        $requerimiento ['tipo'] = "requerimiento";
                        $requerimient [] = $requerimiento;
                        $tareas = Tarea::where([
                                    ['idRequerimiento', $requerimiento['id']],
                                    ['estado', 1]
                                ])->get();
                        if (count($tareas) != 0) {
                            foreach ($tareas as $tarea) {
                                $tarea ['tipo'] = "tarea";
                                $requerimient [] = $tarea;
                            }
                        }
                    }
                }
                
                foreach ($requerimient as $requerimiento) {
                    foreach ($anidados as $anidado) {
                        if ($anidado->idRequerimientoAnexo == $requerimiento['id']) {
                            $estado = false;
                        }
                    }
                    if ($requerimiento['fechaCierre'] == "9999-12-31 00:00:00") {
                        $requerimiento ['status'] = 1;
                    } else 
                    {
                        if($requerimiento['fechaRealCierre'] != ""){
                            $cierre = new Datetime($requerimiento['fechaRealCierre']);
                        } else 
                        {
                            $cierre = new DateTime($requerimiento['fechaCierre']);
                        }
                                    
                        if ($cierre->getTimestamp()<$hoy->getTimestamp()) {
                            $requerimiento ['status'] = 3;
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
                            } else {
                                $requerimiento ['status'] = 1;
                            }
                            $variable = 0;
                            unset($hoy);
                            $hoy = new DateTime();                           
                        }                        
                    }                    
                    if ($requerimiento['fechaRealCierre'] != "") {
                        $cierre_req = new DateTime($requerimiento['fechaRealCierre']);
                        if ($cierre_req->getTimestamp() > $hoy->getTimestamp() and
                                $cierre_req->getTimestamp() < $diez_dia->getTimestamp()){
                            $requerimiento = (object) $requerimiento;
                            $requerimientos[]=$requerimiento;
                        }   
                    } else {
                        $cierre_req = new DateTime($requerimiento['fechaCierre']);
                        if ($cierre_req->getTimestamp() > $hoy->getTimestamp() and
                                $cierre_req->getTimestamp() < $diez_dia->getTimestamp()){
                            $requerimiento = (object) $requerimiento;
                            $requerimientos[]=$requerimiento;
                        }                        
                    }
                }
                
                $valor = 1;
                if ($request->session()->get('tipo') == 1) {
                    $valor = 1;
                } else {
                    $valor = 0;
                }
                $lider = 0;
                if ($user->nombre == "resolutor") {
                    $resolutor = Resolutor::where('idUser', $user[0]->idUser)->first('lider');
                    $lider = $resolutor->lider;
                }
                
                $requerimientos = (object)$requerimientos;
            break;                
        }
        return view('Requerimientos.index2', compact('requerimientos', 'resolutors', 'valor', 'user', 'anidados', 'solicitantes', 'tipo', 'res', 'lider'));
    }

    public function for_priority(Request $request)
    {
        $tipo = $request->tipo;
     
        $resolutors = Resolutor::where('rutEmpresa', auth()->user()->rutEmpresa)->get();
        $solicitantes = Solicitante::where('rutEmpresa', auth()->user()->rutEmpresa)->orderBy('nombreSolicitante')->get();
        
        $anidados = Anidado::all();

        if (isset($request->tipo)) {
            $request->session()->put('tipo', $request->tipo);
        } else {
            $request->session()->put('tipo', "1");
            $tipo = 1;
        }
        
        $user = DB::table('usuarios')->where('idUser', auth()->user()->id)->first();
        $res = Resolutor::where('idUser', $user->idUser)->first(); 
        switch ($tipo) {
            case '1':
                $req = DB::table('requerimientos_equipos')->where([
                            ['estado', 1],
                            ['aprobacion', '=', '3'],
                            ['rutEmpresa', auth()->user()->rutEmpresa],
                            ['resolutor', $res->id],
                            ['idPrioridad', '1'] 
                        ])->get();

                $requerimientos = [];
                
                $hoy = new DateTime(date('Y-m-d'));

                if (count($req) != 0) {
                    foreach ($req as $requerimiento) {
                        $requerimiento = (array) $requerimiento;
                        $requerimiento ['tipo'] = "requerimiento";
                        $requerimient [] = $requerimiento;
                        $tareas = Tarea::where([
                                    ['idRequerimiento', $requerimiento['id']],
                                    ['estado', 1]
                                ])->get();
                        if (count($tareas) != 0) {
                            foreach ($tareas as $tarea) {
                                $tarea ['tipo'] = "tarea";
                                $requerimient [] = $tarea;
                            }
                        }
                    }
                }
                
                foreach ($requerimient as $requerimiento) {
                    foreach ($anidados as $anidado) {
                        if ($anidado->idRequerimientoAnexo == $requerimiento['id']) {
                            $estado = false;
                        }
                    }
                    
                    if ($requerimiento['fechaCierre'] == "9999-12-31 00:00:00") {
                        $requerimiento ['status'] = 1;
                    } else 
                    {
                        if($requerimiento['fechaRealCierre'] != ""){
                            $cierre = new Datetime($requerimiento['fechaRealCierre']);
                        } else 
                        {
                            $cierre = new DateTime($requerimiento['fechaCierre']);
                        }
                                    
                        if ($cierre->getTimestamp()<$hoy->getTimestamp()) {
                            $requerimiento ['status'] = 3;
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
                            } else {
                                $requerimiento ['status'] = 1;
                            }
                            $variable = 0;
                            unset($hoy);
                            $hoy = new DateTime();                           
                        }                        
                    }
                    $requerimiento = (object)$requerimiento;
                    $requerimientos[] = $requerimiento;
                }
                $lider = 0;
                if ($user->nombre == "resolutor") {
                    $resolutor = Resolutor::where('idUser', $user[0]->idUser)->first('lider');
                    $lider = $resolutor->lider;
                }                
                $requerimientos = (object)$requerimientos;
            break;
            
            case '2':
                $req = DB::table('requerimientos_equipos')->where([
                            ['estado', 1],
                            ['aprobacion', '=', '3'],
                            ['rutEmpresa', auth()->user()->rutEmpresa],
                            ['resolutor', $res->id],
                            ['idPrioridad', '2'] 
                        ])->get();

                $requerimientos = [];
                
                $hoy = new DateTime(date('Y-m-d'));

                if (count($req) != 0) {
                    foreach ($req as $requerimiento) {
                        $requerimiento = (array) $requerimiento;
                        $requerimiento ['tipo'] = "requerimiento";
                        $requerimient [] = $requerimiento;
                        $tareas = Tarea::where([
                                    ['idRequerimiento', $requerimiento['id']],
                                    ['estado', 1]
                                ])->get();
                        if (count($tareas) != 0) {
                            foreach ($tareas as $tarea) {
                                $tarea ['tipo'] = "tarea";
                                $requerimient [] = $tarea;
                            }
                        }
                    }
                }
                
                foreach ($requerimient as $requerimiento) {
                    foreach ($anidados as $anidado) {
                        if ($anidado->idRequerimientoAnexo == $requerimiento['id']) {
                            $estado = false;
                        }
                    }
                    
                    if ($requerimiento['fechaCierre'] == "9999-12-31 00:00:00") {
                        $requerimiento ['status'] = 1;
                    } else 
                    {
                        if($requerimiento['fechaRealCierre'] != ""){
                            $cierre = new Datetime($requerimiento['fechaRealCierre']);
                        } else 
                        {
                            $cierre = new DateTime($requerimiento['fechaCierre']);
                        }
                                    
                        if ($cierre->getTimestamp()<$hoy->getTimestamp()) {
                            $requerimiento ['status'] = 3;
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
                            } else {
                                $requerimiento ['status'] = 1;
                            }
                            $variable = 0;
                            unset($hoy);
                            $hoy = new DateTime();                           
                        }                        
                    }
                    $requerimiento = (object)$requerimiento;
                    $requerimientos[] = $requerimiento;
                }
                $lider = 0;
                if ($user->nombre == "resolutor") {
                    $resolutor = Resolutor::where('idUser', $user[0]->idUser)->first('lider');
                    $lider = $resolutor->lider;
                }                
                $requerimientos = (object)$requerimientos;
            break;

            case '3':
                $req = DB::table('requerimientos_equipos')->where([
                            ['estado', 1],
                            ['aprobacion', '=', '3'],
                            ['rutEmpresa', auth()->user()->rutEmpresa],
                            ['resolutor', $res->id],
                            ['idPrioridad', '3'] 
                        ])->get();
                
                if(count($req) == 0){
                    $lider = 0;
                    if ($user->nombre == "resolutor") {
                        $resolutor = Resolutor::where('idUser', $user[0]->idUser)->first('lider');
                        $lider = $resolutor->lider;
                    }                     
                    $requerimientos = [];
                    $requerimientos = (object)$requerimientos;
                } else {
                    $requerimientos = [];

                    $hoy = new DateTime(date('Y-m-d'));

                    if (count($req) != 0) {
                        foreach ($req as $requerimiento) {
                            $requerimiento = (array) $requerimiento;
                            $requerimiento ['tipo'] = "requerimiento";
                            $requerimient [] = $requerimiento;
                            $tareas = Tarea::where([
                                        ['idRequerimiento', $requerimiento['id']],
                                        ['estado', 1]
                                    ])->get();
                            if (count($tareas) != 0) {
                                foreach ($tareas as $tarea) {
                                    $tarea ['tipo'] = "tarea";
                                    $requerimient [] = $tarea;
                                }
                            }
                        }
                    }

                    foreach ($requerimient as $requerimiento) {
                        foreach ($anidados as $anidado) {
                            if ($anidado->idRequerimientoAnexo == $requerimiento['id']) {
                                $estado = false;
                            }
                        }

                        if ($requerimiento['fechaCierre'] == "9999-12-31 00:00:00") {
                            $requerimiento ['status'] = 1;
                        } else 
                        {
                            if($requerimiento['fechaRealCierre'] != ""){
                                $cierre = new Datetime($requerimiento['fechaRealCierre']);
                            } else 
                            {
                                $cierre = new DateTime($requerimiento['fechaCierre']);
                            }

                            if ($cierre->getTimestamp()<$hoy->getTimestamp()) {
                                $requerimiento ['status'] = 3;
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
                                } else {
                                    $requerimiento ['status'] = 1;
                                }
                                $variable = 0;
                                unset($hoy);
                                $hoy = new DateTime();                           
                            }                        
                        }
                        $requerimiento = (object)$requerimiento;
                        $requerimientos[] = $requerimiento;
                    }
                    $lider = 0;
                    if ($user->nombre == "resolutor") {
                        $resolutor = Resolutor::where('idUser', $user[0]->idUser)->first('lider');
                        $lider = $resolutor->lider;
                    }                
                    $requerimientos = (object)$requerimientos;                    
                }
            break;
        }
        
        return view('Requerimientos.index3', compact('requerimientos', 'resolutors', 'user', 'anidados', 'solicitantes', 'tipo', 'res', 'lider'));
    }    

    public function adjuntar(Requerimiento $requerimiento)
    {
        $user = DB::table('usuarios')->where('idUser', auth()->user()->id)->get();
        
        return view('Requerimientos.adjuntar', compact('requerimiento', 'user'));
    }

    public function adjuntar_archivo(Request $request)
    {
        // dd($request);
        $user = DB::table('usuarios')->where('idUser', auth()->user()->id)->get();

        // Guarda el documento adjunto al registrar el requerimiento
        $path = public_path().'/docs/requerimientos/';
        $file = $request->file('files');
        
        if (!empty($_FILES['archivo']['tmp_name']) || is_uploaded_file($_FILES['archivo']['tmp_name']))
        {
            $file = Input::file('archivo'); 
            $filenameWithExt = $request->file('archivo')->getClientOriginalName(); 
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('archivo')->getClientOriginalExtension();
            $filenameToStore = "Req".$request->id_req."_".uniqid().".".$extension;
            \Request::file('archivo')->move($path, $filenameToStore);

            return redirect('requerimientos')->with('msj', 'Archivo adjuntado correctamente al Requerimiento '.$request->id_req2);
        } else {
            return redirect('requerimientos')->with('msj', 'Debe seleccionar un archivo para adjuntar al requerimiento');
        }

    }
}
