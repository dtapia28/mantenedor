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
use App\Notifications\FinalizadoNotifi;
use App\Notifications\RechazadoNotifi;
use Illuminate\Support\Facades\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DateTime;
use DateInterval;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class WhatsmsApi 
            {
                var $apikey;
                    
                public function setApiKey($apikey){
                    $this->apikey = $apikey;
                }
                    
                public function sendSms($phone, $message){
                    if(empty($phone)){
                        return array("status" => false, "message" => "example -> api->sendSms('+5555555555','message'); ");
                    }
                        
                    if(empty($message)){
                        return array("status" => false, "message" => "example -> api->sendSms('+5555555555','message'); ");
                    }
                        
                    if(!is_string($phone) || !is_string($message)){
                        return array("status" => false, "message" => "example -> api->sendSms('string','string'); ");
                    }
                        
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_RETURNTRANSFER => 1,
                        CURLOPT_URL => 'https://whatsmsapi.com/api/send_sms',
                        CURLOPT_USERAGENT => 'WhatsmsApi',
                        CURLOPT_POST => 1,
                        CURLOPT_POSTFIELDS => array(
                            "phone" => $phone,
                            "text" => $message
                        ),
                        CURLOPT_HTTPHEADER => array(
                            "x-api-key: $this->apikey"
                        )
                    ));
                    $resp = curl_exec($curl);
                    if(!$resp){
                        $response = array(
                            "phones" => array(
                                $phone
                            ), 
                            "text" => $message,
                            "sms" => array(
                                ((object) array(
                                    "idsms" => -1,
                                    "success" => false,
                                    "message" => "Error al ejecutar la operación"
                                ))
                            )
                        );
                        return $response;
                    }else{
                        curl_close($curl);
                        return json_decode($resp);
                    }
                }
                    
            }
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
        ])->get();

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
                        ['estado', '=', 1],
                        ['aprobacion', 3],
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
                                $cierre = new DateTime($requerimiento['fechaCierre']);
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
                                $cierre = new DateTime($requerimiento['fechaCierre']);
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
                    case '2':
                    $req = DB::table('requerimientos_equipos')->where([
                        ['estado', '=', 1],
                        ['aprobacion', 3],
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
                                $cierre = new DateTime($requerimiento['fechaCierre']);
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
                        ['aprobacion', 3],
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
                                $cierre = new DateTime($requerimiento['fechaCierre']);
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
                    $arreglo = [];
                    $requerimientos = [];
                    $estado = true;

                    $hoy = new DateTime();
                    foreach ($req as $req2) 
                    {
                        $req2 = (array) $req2;
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
                    $req2 = (object) $req2;
                    $arreglo = (object)$arreglo;
                    foreach ($arreglo as $req2) 
                    {
                        $req2 = (array) $req2;                 
                        if ($req2['fechaRealCierre'] == null) 
                        {
                            if ($req2['fechaCierre'] == "9999-12-31 00:00:00") {


                            } else
                            {
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
                                $cierre = new DateTime($requerimiento['fechaCierre']);
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
                    $req = DB::table('requerimientos_equipos')->where([
                        ['estado', 1],
                        ['rutEmpresa', '=', auth()->user()->rutEmpresa],
                        ['idEquipo', $equipo->id],
                        ['aprobacion','=',4],
                        ['resolutor', '!=', $res->id],
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
                                $cierre = new DateTime($requerimiento['fechaCierre']);
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
                                $cierre = new DateTime($requerimiento->fechaCierre);
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
                                $cierre = new DateTime($requerimiento->fechaCierre);
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
            } else 
            {
                $res = Resolutor::where('idUser', $user[0]->idUser)->first();
                switch ($request->session()->get('state')) 
                {
                    case '1':
                    $req = Requerimiento::orderBy('fechaSolicitud', 'desc')->where([
                        ['estado', 1],
                        ['aprobacion', 3],
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
                                $cierre = new DateTime($requerimiento->fechaCierre);
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
                                $cierre = new DateTime($requerimiento->fechaCierre);
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
                        ['aprobacion', 3],
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
                                $cierre = new DateTime($requerimiento->fechaCierre);
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
                        ['aprobacion', 3],
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
                                $cierre = new DateTime($requerimiento->fechaCierre);
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
                    if ($req2->fechaRealCierre == null) 
                    {
                        if ($req2->fechaCierre != "9999-12-31 00:00:00") 
                        {
                            $cierre = new DateTime($req2->fechaCierre);
                            if ($hoy->getTimestamp()>$cierre->getTimestamp()) 
                            {
                                $requerimientos[] = $req2;                            
                            }
                        }    
                    } else 
                    {
                        $requerimientos[] = $req2;                       
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
                                $cierre = new DateTime($requerimiento->fechaCierre);
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
                                $cierre = new DateTime($requerimiento->fechaCierre);
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
                                $cierre = new DateTime($requerimiento->fechaCierre);
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
                    ['aprobacion', 3],
                    ['rutEmpresa', '=', auth()->user()->rutEmpresa],
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
                    ['aprobacion', 3],
                    ['porcentajeEjecutado', '>=', $request->valorN],
                    ['rutEmpresa', '=', auth()->user()->rutEmpresa],
                ])->get();
                    break;
                case '3':
                $requerimientos = Requerimiento::orderBy('fechaSolicitud', 'desc')->where([
                    ['estado', 1],
                    ['aprobacion', 3],
                    ['porcentajeEjecutado', '<=', $request->valorN],
                    ['rutEmpresa', '=', auth()->user()->rutEmpresa],
                ])->get();
                    break;
            }
        } elseif ($user[0]->nombre == "supervisor") 
        {
            $res = Resolutor::where('idUser', $user[0]->idUser)->first();
            switch ($request->session()->get('state'))
            {
                case '1':
                $req = Requerimiento::where([
                    ['estado', 1],
                    ['aprobacion', 3],
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
                            $cierre = new DateTime($requerimiento->fechaCierre);
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
                            $cierre = new DateTime($requerimiento->fechaCierre);
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
                    ['aprobacion', 3],
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
                            $cierre = new DateTime($requerimiento->fechaCierre);
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
                    ['aprobacion', 3],
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
                            $cierre = new DateTime($requerimiento->fechaCierre);
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
                    if ($req2->fechaRealCierre == null) 
                    {
                        if ($req2->fechaCierre == "9999-12-31 00:00:00") {

                        } else
                        {
                            $cierre = new DateTime($req2->fechaCierre);
                            if ($hoy->getTimestamp()>$cierre->getTimestamp()) 
                            {
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
                                }
                                $requerimientos[] = $req2;                            
                            }
                        }    
                    } else 
                    {
                        $requerimientos[] = $req2;                       
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
                            $cierre = new DateTime($requerimiento->fechaCierre);
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
                            $cierre = new DateTime($requerimiento['fechaCierre']);
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
        } 
        else 
        {
            $res = Resolutor::where('idUser', $user[0]->idUser)->first();
            switch ($request->session()->get('state')) 
            {
                case '1':
                $req = Requerimiento::where([
                    ['estado', 1],
                    ['aprobacion', 3],
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
	                        $cierre = new DateTime($requerimiento->fechaCierre);
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
	                        $cierre = new DateTime($requerimiento->fechaCierre);
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
                    ['aprobacion', 3],
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
	                        $cierre = new DateTime($requerimiento->fechaCierre);
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
                    ['aprobacion', 3],
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
	                        $cierre = new DateTime($requerimiento->fechaCierre);
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
                    if ($req2->fechaRealCierre == null) 
                    {
                    	if ($req2->fechaCierre == "9999-12-31 00:00:00") {

                    	} else
                    	{
	                        $cierre = new DateTime($req2->fechaCierre);
	                        if ($hoy->getTimestamp()>$cierre->getTimestamp()) 
	                        {
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
	                            }
	                            $requerimientos[] = $req2;                            
	                        }
	                    }    
                    } else 
                    {
                        $requerimientos[] = $req2;                       
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
	                        $cierre = new DateTime($requerimiento->fechaCierre);
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
                            $cierre = new DateTime($requerimiento['fechaCierre']);
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
                                $cierre = new DateTime($requerimiento->fechaCierre);
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

        if ($fechaCie->getTimestamp() >= $fechaSoli->getTimestamp()) 
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
                
                $conteo = 1;
                foreach ($resolutors as $resolutor) {
                    if ($resolutor->idTeam == $team[0]->id) {
                        foreach ($requerimientos as $requerimiento) {
                            if ($requerimiento->resolutor == $resolutor->id) {
                                $conteo++;
                            }
                        }
                    }
                }

                if ($conteo < 10) {
                    $conteoA = "00".$conteo;
                } elseif ($conteo >= 10 and $conteo <= 99){
                    $conteoA = "0".$conteo;
                } else {
                    $conteoA = $conteo;
                }
                if ($request->idTipo == 1) {
                    $var = "RQ-".$team[0]->id2."-".$conteoA;
                } else {
                    $var = "INC-".$team[0]->id2."-".$conteoA;
                }
                
              

            Requerimiento::create([
                'textoRequerimiento' => $data['textoRequerimiento'],
                'comentario' => $data['comentario'],            
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

            $conteo = 1;

            $req = Requerimiento::where('textoRequerimiento', $data['textoRequerimiento'])->get();

            $user = User::where([
                ['name', auth()->user()->name],
                ['rutEmpresa', auth()->user()->rutEmpresa],
            ])->get();

            LogRequerimientos::create([
                'idRequerimiento' => $req[0]->id,
                'idUsuario' => $user[0]->id,
                'tipo' => 'Creación',
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
        
//            Notification::route('mail', $recep)->notify(new NewReqResolutor($obj));
            
            $id = $requerimiento->id2;
            $link = "http://app.kinchika.com/requerimientos/".$requerimiento->id;

            $whatsmsapi = new WhatsmsApi();
            $whatsmsapi->setApiKey("5e2edfe1aa0f9");
            $whatsmsapi->sendSms("56953551286", "Hola soy Kinchika y te estoy enviando este mensaje porque se te ha asignado el siguiente requerimiento: ".$id."\n Solicitud: ".$requerimiento->textoRequerimiento."\n Puedes ver el detalle del requerimiento en el siguiente link: ".$link);
            
            if ($request->idTipo == 1) {
                return redirect('requerimientos')->with('msj', 'Requerimiento '.$requerimiento->id2.' guardado correctamente');
            } else {
                return redirect('requerimientos')->with('msj', 'Incidente '.$requerimiento->id2.' guardado correctamente');
            }
        }else 
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
        $tareas = Tarea::where('idRequerimiento', $requerimiento->id)->get();
        $resolutores = [];
        foreach ($tareas as $tarea) 
        {
            $resolutor = Resolutor::where('id', $tarea->resolutor)->first();
            $resolutores [] = $resolutor;
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

        return view('Requerimientos.show', compact('user','requerimiento', 'resolutors', 'priorities', 'avances', 'teams', 'fechaCierre', 'requerimientosAnidados', 'tareas', 'requerimientos', 'solicitante', 'resolutor', 'resolutores', 'lider', 'res', 'id2'));        
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

        $solicitantes = Solicitante::where('rutEmpresa', auth()->user()->rutEmpresa)->get();
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
        
        return view('Requerimientos.edit', compact('requerimiento', 'solicitantes', 'priorities', 'resolutors', 'fechaCierre', 'cierre', 'solicitud', 'solicitanteEspecifico', 'prioridadEspecifica', 'resolutorEspecifico', 'user', 'lider', 'teams'));        
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
            'resolutor' => 'nullable',
            'fechaCierre' => 'nullable',
            'textAvance' => 'nullable',            
            'fechaSolicitud' => 'nullable',
        ]);

        $data['idEmpresa'] = auth()->user()->rutEmpresa;

        $user = User::where([
            ['name', auth()->user()->name],
            ['rutEmpresa', auth()->user()->rutEmpresa],
        ])->get();

        if ($data['textoRequerimiento'] != $requerimiento->textoRequerimiento) {
            LogRequerimientos::create([
                'idRequerimiento' => $requerimiento->id,
                'idUsuario' => $user[0]->id,
                'tipo' => 'Edición',
                'campo' => 'texto Requerimiento',
            ]);             
        }

        if ($data['idSolicitante'] != $requerimiento->idSolicitante) {
            LogRequerimientos::create([
                'idRequerimiento' => $requerimiento->id,
                'idUsuario' => $user[0]->id,
                'tipo' => 'Edición',
                'campo' => 'id solicitante',
            ]);              
        }

        if ($data['idPrioridad'] != $requerimiento->idPrioridad) {
            LogRequerimientos::create([
                'idRequerimiento' => $requerimiento->id,
                'idUsuario' => $user[0]->id,
                'tipo' => 'Edición',
                'campo' => 'id prioridad',
            ]);             
        }

        if ($data['resolutor'] != $requerimiento->resolutor) {
            LogRequerimientos::create([
                'idRequerimiento' => $requerimiento->id,
                'idUsuario' => $user[0]->id,
                'tipo' => 'Edición',
                'campo' => 'id resolutor',
            ]);
        } 

        $fechita = str_split($requerimiento->fechaCierre);
        $fechota = [];
        for ($i=0; $i < 10; $i++) { 
            $b = strtoupper($fechita[$i]);
            array_push($fechota, $b);
        }
        $cierre = implode("", $fechota);                

        if ($data['fechaCierre'] != $cierre) {
            LogRequerimientos::create([
                'idRequerimiento' => $requerimiento->id,
                'idUsuario' => $user[0]->id,
                'tipo' => 'Edición',
                'campo' => 'fecha cierre',
            ]);
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
                'tipo' => 'Edición',
                'campo' => 'fecha solicitud',
            ]);
        }                          
              
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
            'tipo' => 'Eliminación',
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
                LogRequerimientos::create([
                    'idRequerimiento' => $requerimiento->id,
                    'idUsuario' => $user[0]->id,
                    'tipo' => 'Crea',
                    'campo' => 'Avance',
                ]);  

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
                LogRequerimientos::create([
                    'idRequerimiento' => $requerimiento->id,
                    'idUsuario' => $user[0]->id,
                    'tipo' => 'Crea',
                    'campo' => 'Avance',
                ]);  

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
        $user = DB::table('usuarios')->where('idUser', auth()->user()->id)->get();
        $lider = 0;
        if ($user[0]->nombre == "resolutor") {
            $resolutor = Resolutor::where('idUser', $user[0]->idUser)->first('lider');
            $lider = $resolutor->lider;           
        }       
        return view('Requerimientos.terminado', compact('requerimiento', 'user', 'lider'));        
    } 

    public function guardar(Request $request, Requerimiento $requerimiento)
    {
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
        ];           
       } else {
        $data = [
            'estado' => 1,
            'porcentajeEjecutado' => 100,
            'cierre' => $data['cierre'],
            'fechaRealCierre' => $data['fechaRealCierre'],
            'aprobacion' => 4,
        ];      
       }
        $variable =$requerimiento->update($data);
        
        $resolutor = Resolutor::where('id', $requerimiento->resolutor)->first();
        if ($resolutor->lider == 0) {
            $solicitante = Solicitante::where('id', $requerimiento->idSolicitante)->first();
            $resolutores = Resolutor::where('idTeam',Team::where('id',$resolutor->idTeam)->first()->id)->get();
            foreach ($resolutores as $resol) {
                if ($resol->lider == 1) {
                    $lider = $resol;
                }
            }
            $obj = new \stdClass();
            $obj->idReq = $requerimiento->id2;
            $obj->id = $requerimiento->id;
            $obj->sol = $requerimiento->textoRequerimiento;
            $obj->nombre = $resolutor->nombreResolutor;
            $obj->solicitante = $solicitante->nombreSolicitante;

            $recep = $lider->email;

            Notification::route('mail', $recep)->notify(new FinalizadoNotifi($obj));
        } else 
        {
            $solicitante = Solicitante::where('id', $requerimiento->idSolicitante)->first();
            $parametros = Parametros::where('rutEmpresa', auth()->user()->rutEmpresa)->first();
            $obj = new \stdClass();
            $obj->idReq = $requerimiento->id2;
            $obj->id = $requerimiento->id;
            $obj->sol = $requerimiento->textoRequerimiento;
            $obj->nombre = $resolutor->nombreResolutor;
            $obj->solicitante = $solicitante->nombreSolicitante;
            $email = $parametros->emailSupervisor;

            Notification::route('mail', $email)->notify(new FinalizadoNotifi($obj));
        }    
        return redirect('requerimientos'); 
    }

    public function activar(Requerimiento $requerimiento){
        $data = [
            'estado' => 1,
            'porcentajeEjecutado' => 80,
            'cierre' => "",
            'aprobacion' => 3,
        ];
        $requerimiento->update($data);
        
        return redirect('requerimientos');   
    }

    public function autorizar(Requerimiento $requerimiento)
    {
        $data = [
            'estado' => 2,
            'aprobacion' => 1,
        ];
        $requerimiento->update($data);

        return redirect('requerimientos');
    }

    public function aceptar($requerimiento) {
        $req = Requerimiento::where('id', $requerimiento)->first();
        self::autorizar($req);

        return back()->with('msj', 'Requerimiento autorizado');
    }

    public function rechazar($requerimiento) {
        $user = DB::table('usuarios')->where('idUser', auth()->user()->id)->get();
        $lider = 0;
        if ($user[0]->nombre == "resolutor") {
            $resolutor = Resolutor::where('idUser', $user[0]->idUser)->first('lider');
            $lider = $resolutor->lider;           
        }   
        return view('Requerimientos.rechazar', compact('requerimiento', 'user', 'lider'));
    }

    public function RequerimientoRechazado(Request $request) {
        $req = Requerimiento::where('id', $request->requerimiento)->first();
        $data = [
            'estado' => 1,
            'aprobacion' => 2,
            'rechazo' => $request->rechazo,
        ];
        $req->update($data);

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

        return redirect('requerimientos');
    }
}
