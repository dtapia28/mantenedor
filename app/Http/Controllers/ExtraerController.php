<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\EstadoExport;
use App\Exports\EjecutadoExport;
use App\Exports\CambiosExport;
use App\Exports\SolicitantesExport;
use App\Exports\ResolutorsExport;
use App\Solicitante;
use App\Resolutor;
use App\Requerimiento;
use App\Team;
use App\Avance;
use Illuminate\Support\Facades\DB;
use DateTime;

class ExtraerController extends Controller
{

    public function index()
    {
        $user = DB::table('usuarios')->where('idUser', auth()->user()->id)->get();
        $lider = 0;
        if ($user[0]->nombre == "resolutor") {
            $resolutor = Resolutor::where('idUser', $user[0]->idUser)->first('lider');
            $lider = $resolutor->lider;           
        }

        if ($user[0]->nombre == "resolutor") {
            $res = Resolutor::where('idUser', $user[0]->idUser)->first('idTeam');
            $equipo = Team::where('id',$res->idTeam)->first();

            $solicitantes = Solicitante::where('rutEmpresa', auth()->user()->rutEmpresa)->orderBy('nombreSolicitante')->get();

            $resolutors = Resolutor::where([
                ['rutEmpresa', auth()->user()->rutEmpresa],
                ['idTeam', $equipo->id],
            ])->orderBy('nombreResolutor')->get();

            $teams = Team::where([
                ['rutEmpresa', auth()->user()->rutEmpresa],
            ])->orderBy('nameTeam')->get();  

        } else {
            $solicitantes = Solicitante::where('rutEmpresa', auth()->user()->rutEmpresa)->orderBy('nombreSolicitante')->get();

            $resolutors = Resolutor::where([
                ['rutEmpresa', auth()->user()->rutEmpresa],
            ])->orderBy('nombreResolutor')->get();

            $teams = Team::where([
                ['rutEmpresa', auth()->user()->rutEmpresa],
            ])->orderBy('nameTeam')->get();
        }                 
    
        return view('Extraer.index', compact('solicitantes', 'resolutors', 'teams', 'user', 'lider'));
    }

    public function word(Request $request)
    {
        $solicitante = Solicitante::where('id', $request['solicitante'])->first();
        $requerimientos = Requerimiento::where([
            ['idSolicitante', $solicitante->id],
            ['estado', 1],
            ['rutEmpresa', auth()->user()->rutEmpresa],
        ])->get();

        $resolutores = Resolutor::where('rutEmpresa', auth()->user()->rutEmpresa)->get();
        $total = count($requerimientos);
        $hoy = new DateTime();
        $texto = "
        <p style = 'font-family:verdana; font-size:10pt;'  align='center'><strong>Informe de requerimientos por solicitante</strong>\n
        <br><strong>".$hoy->format('d-m-Y')."</strong></p>
        <p style = 'font-family:verdana; font-size:9pt;'>".$solicitante->nombreSolicitante." tiene un total de ".$total." requerimientos activos en el sistema.</p>
        <p style ='font-family:verdana; font-size:9pt;'>El estado de sus requerimientos es el siguiente: </p>
        <table style ='font-family:verdana; font-size:9pt;' border='1'>
        <tr align='center'>
            <td><strong>id</strong></td>
            <td><strong>Solicitud</strong></td>
            <td><strong>Fecha Solicitud</strong></td>
            <td><strong>Fecha Cierre</strong></td>
            <td><strong>Resolutor</strong></td>
            <td><strong>".utf8_decode("&#218ltimo Avance")."</strong></td>
        </tr>";
        foreach ($requerimientos as $req) {
            $id2 = substr($req->id2,3,7);
            $avance = Avance::where('idRequerimiento', $req->id)->latest()->first();
            $texto.="<tr align='justify'; style = 'font-family:verdana; font-size:9pt;'>";
            $texto.="<td>".$id2."</td>";
            $texto.="<td>".utf8_decode($req->textoRequerimiento)."</td>";
            $texto.="<td align='center'>".date('d-m-Y', strtotime($req->fechaSolicitud))."</td>";
            $texto.="<td align='center'>".date('d-m-Y', strtotime($req->fechaCierre))."</td>";
            foreach ($resolutores as $resolutor) {
                if ($resolutor->id == $req->resolutor) {
                    $texto.="<td>".utf8_decode($resolutor->nombreResolutor)."</td>";
                } 
            }
            if ($avance != null) {
                $texto.="<td>".utf8_decode($avance->textAvance)."</td>";
            } else {
                $texto.="<td></td>";                
            }          
            $texto.="</tr>";
        }
        $hoy = new DateTime();
        $texto.="</table>"; 


        if (empty($texto)) {
            return back()->with('msj', 'No existen requerimientos que cumplan con su solicitud.');                
        } else {      
            return view('Extraer.index', compact('texto'));
        }        
    }

    public function porEstado(Request $request)
    {
        $user = DB::table('usuarios')->where('idUser', auth()->user()->id)->first();
        if ($user->nombre == "resolutor") {
            $res = Resolutor::where('idUser', $user->idUser)->first('idTeam');
            $equipo = Team::where('id',$res->idTeam)->first();
            switch ($request['estado']) 
            {
                case '1':
                    $base = DB::table('requ_view')->where([
                        ['rutEmpresa', auth()->user()->rutEmpresa],
                        ['estado', 1],
                        ['teamId', $equipo->id],
                    ])->get(['id2 as id', 'textoRequerimiento', 'fechaEmail AS Fecha de Email', 'fechaSolicitud AS Fecha de solicitud', 'fechaCierre AS Fecha de cierre', 'fechaRealCierre AS Fecha real de cierre', 'porcentajeEjecutado AS Porcentaje ejecutado', 'nombreSolicitante AS Solicitante', 'nombreResolutor AS Resolutor', 'nameTeam AS Equipo', 'namePriority AS Prioridad', 'textAvance AS Avance'])->toArray();
                break;
                case '2':
                    $base = DB::table('requ_view')->where([
                        ['rutEmpresa', auth()->user()->rutEmpresa],
                        ['estado', 2],
                        ['teamId', $equipo->id],
                    ])->get(['id2 as id', 'textoRequerimiento', 'fechaEmail AS Fecha de Email', 'fechaSolicitud AS Fecha de solicitud', 'fechaCierre AS Fecha de cierre', 'fechaRealCierre AS Fecha real de cierre', 'porcentajeEjecutado AS Porcentaje ejecutado', 'nombreSolicitante AS Solicitante', 'nombreResolutor AS Resolutor', 'nameTeam AS Equipo', 'namePriority AS Prioridad', 'textAvance AS Avance'])->toArray();
                break;
                case '3':
                    $base = DB::table('requ_view')->where([
                        ['rutEmpresa', auth()->user()->rutEmpresa],
                        ['teamId', $equipo->id],
                    ])->get(['id2 as id', 'textoRequerimiento', 'fechaEmail AS Fecha de Email', 'fechaSolicitud AS Fecha de solicitud', 'fechaCierre AS Fecha de cierre', 'fechaRealCierre AS Fecha real de cierre', 'porcentajeEjecutado AS Porcentaje ejecutado', 'nombreSolicitante AS Solicitante', 'nombreResolutor AS Resolutor', 'nameTeam AS Equipo', 'namePriority AS Prioridad', 'textAvance AS Avance'])->toArray();
                break;
                case '4':
                    $base1 = DB::table('requ_view')->where([
                        ['rutEmpresa', auth()->user()->rutEmpresa],
                        ['estado', 1],
                        ['porcentajeEjecutado', '<', 100],
                        ['teamId', $equipo->id],
                    ])->get(['id2 as id', 'textoRequerimiento', 'fechaEmail AS Fecha de Email', 'fechaSolicitud AS Fecha de solicitud', 'fechaCierre AS Fecha de cierre', 'fechaRealCierre AS Fecha real de cierre', 'porcentajeEjecutado AS Porcentaje ejecutado', 'nombreSolicitante AS Solicitante', 'nombreResolutor AS Resolutor', 'nameTeam AS Equipo', 'namePriority AS Prioridad', 'textAvance AS Avance'])->toArray();

                    $base = [];
                    $hoy = new DateTime();
                    foreach ($base1 as $req2) 
                    {
                        $req2 = (array) $req2;
                        if ($req2['Fecha de cierre'] == "9999-12-31 00:00:00") {

                        } else
                        {
                            if($requerimiento['fechaRealCierre'] != ""){
                                $cierre = new Datetime($requerimiento['fechaRealCierre']);
                            } else {
                                $cierre = new DateTime($requerimiento['fechaCierre']);
                            }
                            if ($hoy->getTimestamp()>$cierre->getTimestamp()) 
                            {
                                $req2 = (object)$req2;

                                $base [] = $req2;
                            }
                        }       
                    }
                break;               
            }
        } else 
        {
            switch ($request['estado']) 
            {
                case '1':
                    $base = DB::table('requ_view')->where([
                        ['rutEmpresa', auth()->user()->rutEmpresa],
                        ['estado', 1],
                    ])->get(['id2 as id', 'textoRequerimiento', 'fechaEmail AS Fecha de Email', 'fechaSolicitud AS Fecha de solicitud', 'fechaCierre AS Fecha de cierre', 'fechaRealCierre AS Fecha real de cierre', 'porcentajeEjecutado AS Porcentaje ejecutado', 'nombreSolicitante AS Solicitante', 'nombreResolutor AS Resolutor', 'nameTeam AS Equipo', 'namePriority AS Prioridad', 'textAvance AS Avance'])->toArray();
                break;
                case '2':
                    $base = DB::table('requ_view')->where([
                        ['rutEmpresa', auth()->user()->rutEmpresa],
                        ['estado', 2],
                    ])->get(['id2 as id', 'textoRequerimiento', 'fechaEmail AS Fecha de Email', 'fechaSolicitud AS Fecha de solicitud', 'fechaCierre AS Fecha de cierre', 'fechaRealCierre AS Fecha real de cierre', 'porcentajeEjecutado AS Porcentaje ejecutado', 'nombreSolicitante AS Solicitante', 'nombreResolutor AS Resolutor', 'nameTeam AS Equipo', 'namePriority AS Prioridad', 'textAvance AS Avance'])->toArray();
                break;
                case '3':
                    $base = DB::table('requ_view')->where([
                        ['rutEmpresa', auth()->user()->rutEmpresa],
                    ])->get(['id2 as id', 'textoRequerimiento', 'fechaEmail AS Fecha de Email', 'fechaSolicitud AS Fecha de solicitud', 'fechaCierre AS Fecha de cierre', 'fechaRealCierre AS Fecha real de cierre', 'porcentajeEjecutado AS Porcentaje ejecutado', 'nombreSolicitante AS Solicitante', 'nombreResolutor AS Resolutor', 'nameTeam AS Equipo', 'namePriority AS Prioridad', 'textAvance AS Avance'])->toArray();
                break;
                case '4':
                    $base1 = DB::table('requ_view')->where([
                        ['rutEmpresa', auth()->user()->rutEmpresa],
                        ['estado', 1],
                    ])->get(['id2 as id', 'textoRequerimiento', 'fechaEmail AS Fecha de Email', 'fechaSolicitud AS Fecha de solicitud', 'fechaCierre AS Fecha de cierre', 'fechaRealCierre AS Fecha real de cierre', 'porcentajeEjecutado AS Porcentaje ejecutado', 'nombreSolicitante AS Solicitante', 'nombreResolutor AS Resolutor', 'nameTeam AS Equipo', 'namePriority AS Prioridad', 'textAvance AS Avance'])->toArray();

                    $base = [];
                    $hoy = new DateTime();
                    foreach ($base1 as $req2) 
                    {
                        $req2 = (array) $req2;
                        if ($req2['Fecha de cierre'] == "9999-12-31 00:00:00") {

                        } else
                        {
                            if($req2['Fecha real de cierre'] != ""){
                                $cierre = new Datetime($req2['Fecha real de cierre']);
                            } else {
                                $cierre = new DateTime($req2['Fecha de cierre']);
                            }
                            if ($hoy->getTimestamp()>$cierre->getTimestamp()) 
                            {
                                $req2 = (object)$req2;

                                $base [] = $req2;
                            }
                        }       
                    }
                break;               
            }
        }
        $base2 = [];
        $requerimientos = [];
        for ($i=0; $i < count($base); $i++) {
            $base[$i]->textoRequerimiento = utf8_decode($base[$i]->textoRequerimiento);
            $base[$i]->Solicitante = utf8_decode($base[$i]->Solicitante);
            $base[$i]->Resolutor = utf8_decode($base[$i]->Resolutor);
            $base[$i]->Equipo = utf8_decode($base[$i]->Equipo);
            $base[$i]->Avance = utf8_decode($base[$i]->Avance);
            $base2 = (array) $base[$i];
            array_push($requerimientos, $base2);
        }
        
        if (empty($requerimientos)) {
            return back()->with('msj', 'No existen requerimientos que cumplan con su solicitud.');                
        } else {         
            return view('Extraer.index', compact('requerimientos'));
        } 
    } 

    public function porEjecutado(Request $request)
    {
        $user = DB::table('usuarios')->where('idUser', auth()->user()->id)->first();
        if ($user->nombre == "resolutor") {
            $res = Resolutor::where('idUser', $user->idUser)->first('idTeam');
            $equipo = Team::where('id',$res->idTeam)->first();
            if ($request['comparacion'] == 1) {
                $base = DB::table('requ_view')->where([
                    ['porcentajeEjecutado', '<=', $request['porcentaje']],
                    ['rutEmpresa', auth()->user()->rutEmpresa],
                    ['estado', 1],
                    ['teamId', $equipo->id],            
                ])->get(['id2 as id', 'textoRequerimiento', 'fechaEmail AS Fecha de Email', 'fechaSolicitud AS Fecha de solicitud', 'fechaCierre AS Fecha de cierre', 'fechaRealCierre AS Fecha real de cierre', 'porcentajeEjecutado AS Porcentaje ejecutado', 'nombreSolicitante AS Solicitante', 'nombreResolutor AS Resolutor', 'nameTeam AS Equipo', 'namePriority AS Prioridad', 'textAvance AS Avance'])->toArray();
            } else {
                $base = DB::table('requ_view')->where([
                    ['porcentajeEjecutado', '>', $request['porcentaje']],
                    ['rutEmpresa', auth()->user()->rutEmpresa],
                    ['estado', 1],
                    ['teamId', $equipo->id],      
                ])->get(['id2 as id', 'textoRequerimiento', 'fechaEmail AS Fecha de Email', 'fechaSolicitud AS Fecha de solicitud', 'fechaCierre AS Fecha de cierre', 'fechaRealCierre AS Fecha real de cierre', 'porcentajeEjecutado AS Porcentaje ejecutado', 'nombreSolicitante AS Solicitante', 'nombreResolutor AS Resolutor', 'nameTeam AS Equipo', 'namePriority AS Prioridad', 'textAvance AS Avance'])->toArray();
            }            
        } else {
            if ($request['comparacion'] == 1) {
                $base = DB::table('requ_view')->where([
                    ['porcentajeEjecutado', '<=', $request['porcentaje']],
                    ['rutEmpresa', auth()->user()->rutEmpresa],
                    ['estado', 1],            
                ])->get(['id2 as id', 'textoRequerimiento', 'fechaEmail AS Fecha de Email', 'fechaSolicitud AS Fecha de solicitud', 'fechaCierre AS Fecha de cierre', 'fechaRealCierre AS Fecha real de cierre', 'porcentajeEjecutado AS Porcentaje ejecutado', 'nombreSolicitante AS Solicitante', 'nombreResolutor AS Resolutor', 'nameTeam AS Equipo', 'namePriority AS Prioridad', 'textAvance AS Avance'])->toArray();
            } else {
                $base = DB::table('requ_view')->where([
                    ['porcentajeEjecutado', '>', $request['porcentaje']],
                    ['rutEmpresa', auth()->user()->rutEmpresa],
                    ['estado', 1],                
                ])->get(['id2 as id', 'textoRequerimiento', 'fechaEmail AS Fecha de Email', 'fechaSolicitud AS Fecha de solicitud', 'fechaCierre AS Fecha de cierre', 'fechaRealCierre AS Fecha real de cierre', 'porcentajeEjecutado AS Porcentaje ejecutado', 'nombreSolicitante AS Solicitante', 'nombreResolutor AS Resolutor', 'nameTeam AS Equipo', 'namePriority AS Prioridad', 'textAvance AS Avance'])->toArray();
            }
        }

        $base2 = [];
        $requerimientos = [];
        for ($i=0; $i < count($base); $i++) {
            $base[$i]->textoRequerimiento = utf8_decode($base[$i]->textoRequerimiento);
            $base[$i]->Solicitante = utf8_decode($base[$i]->Solicitante);
            $base[$i]->Resolutor = utf8_decode($base[$i]->Resolutor);            
            $base[$i]->Equipo = utf8_decode($base[$i]->Equipo);
            $base[$i]->Avance = utf8_decode($base[$i]->Avance);                              
            $base2 = (array) $base[$i];
            array_push($requerimientos, $base2);
        }        
        if (empty($requerimientos)) {
            return back()->with('msj', 'No existen requerimientos que cumplan con su solicitud.');                
        } else {         
            return view('Extraer.index', compact('requerimientos'));
        }  
    }

    public function cambios(Request $request)
    {
        $user = DB::table('usuarios')->where('idUser', auth()->user()->id)->first();
        if ($user->nombre == "resolutor") {
            $res = Resolutor::where('idUser', $user->idUser)->first('idTeam');
            $equipo = Team::where('id',$res->idTeam)->first();

            if ($request['comparacion'] == 1) {
                $base = DB::table('requ_view')->where([
                    ['numeroCambios', '<=', $request['cambios']],
                    ['rutEmpresa', auth()->user()->rutEmpresa],
                    ['estado', 1],
                    ['teamId', $equipo->id],               
                ])->get(['id2 as id', 'textoRequerimiento', 'fechaEmail AS Fecha de Email', 'fechaSolicitud AS Fecha de solicitud', 'fechaCierre AS Fecha de cierre', 'fechaRealCierre AS Fecha real de cierre', 'porcentajeEjecutado AS Porcentaje ejecutado', 'nombreSolicitante AS Solicitante', 'nombreResolutor AS Resolutor', 'nameTeam AS Equipo', 'namePriority AS Prioridad', 'textAvance AS Avance'])->toArray();
            } else {
                $base = DB::table('requ_view')->where([
                    ['numeroCambios', '>', $request['cambios']],
                    ['rutEmpresa', auth()->user()->rutEmpresa],
                    ['estado', 1],
                    ['teamId', $equipo->id],      
                ])->get(['id2 as id', 'textoRequerimiento', 'fechaEmail AS Fecha de Email', 'fechaSolicitud AS Fecha de solicitud', 'fechaCierre AS Fecha de cierre', 'fechaRealCierre AS Fecha real de cierre', 'porcentajeEjecutado AS Porcentaje ejecutado', 'nombreSolicitante AS Solicitante', 'nombreResolutor AS Resolutor', 'nameTeam AS Equipo', 'namePriority AS Prioridad', 'textAvance AS Avance'])->toArray();
            }
        } else {
            if ($request['comparacion'] == 1) {
                $base = DB::table('requ_view')->where([
                    ['numeroCambios', '<=', $request['cambios']],
                    ['rutEmpresa', auth()->user()->rutEmpresa],
                    ['estado', 1],               
                ])->get(['id2 as id', 'textoRequerimiento', 'fechaEmail AS Fecha de Email', 'fechaSolicitud AS Fecha de solicitud', 'fechaCierre AS Fecha de cierre', 'fechaRealCierre AS Fecha real de cierre', 'porcentajeEjecutado AS Porcentaje ejecutado', 'nombreSolicitante AS Solicitante', 'nombreResolutor AS Resolutor', 'nameTeam AS Equipo', 'namePriority AS Prioridad', 'textAvance AS Avance'])->toArray();
            } else {
                $base = DB::table('requ_view')->where([
                    ['numeroCambios', '>', $request['cambios']],
                    ['rutEmpresa', auth()->user()->rutEmpresa],
                    ['estado', 1],                
                ])->get(['id2 as id', 'textoRequerimiento', 'fechaEmail AS Fecha de Email', 'fechaSolicitud AS Fecha de solicitud', 'fechaCierre AS Fecha de cierre', 'fechaRealCierre AS Fecha real de cierre', 'porcentajeEjecutado AS Porcentaje ejecutado', 'nombreSolicitante AS Solicitante', 'nombreResolutor AS Resolutor', 'nameTeam AS Equipo', 'namePriority AS Prioridad', 'textAvance AS Avance'])->toArray();
            }            
        }    

        $base2 = [];
        $requerimientos = [];
        for ($i=0; $i < count($base); $i++) {
            $base[$i]->textoRequerimiento = utf8_decode($base[$i]->textoRequerimiento);
            $base[$i]->Solicitante = utf8_decode($base[$i]->Solicitante);
            $base[$i]->Resolutor = utf8_decode($base[$i]->Resolutor);            
            $base[$i]->Equipo = utf8_decode($base[$i]->Equipo);
            $base[$i]->Avance = utf8_decode($base[$i]->Avance);                                
            $base2 = (array) $base[$i];
            array_push($requerimientos, $base2);
        }

        if (empty($requerimientos)) {
            return back()->with('msj', 'No existen requerimientos que cumplan con su solicitud.');                
        } else {         
            return view('Extraer.index', compact('requerimientos'));
        }    
    }

    public function solicitantes(Request $request)
    {
        $user = DB::table('usuarios')->where('idUser', auth()->user()->id)->first();
        if ($user->nombre == "resolutor") {
            $res = Resolutor::where('idUser', $user->idUser)->first('idTeam');
            $equipo = Team::where('id',$res->idTeam)->first();

            if ($request['idSolicitante'] != "") {
                $base = DB::table('requ_view')->where([
                    ['rutEmpresa', auth()->user()->rutEmpresa],
                    ['idSolicitante', $request['idSolicitante']],
                    ['estado', 1],
                    ['teamId', $equipo->id],
                ])->get(['id2 as id', 'textoRequerimiento', 'fechaEmail AS Fecha de Email', 'fechaSolicitud AS Fecha de solicitud', 'fechaCierre AS Fecha de cierre', 'fechaRealCierre AS Fecha real de cierre', 'porcentajeEjecutado AS Porcentaje ejecutado', 'nombreSolicitante AS Solicitante', 'nombreResolutor AS Resolutor', 'nameTeam AS Equipo', 'namePriority AS Prioridad', 'textAvance AS Avance'])->toArray();
            }
        } else {
            if ($request['idSolicitante'] != "") {
                $base = DB::table('requ_view')->where([
                    ['rutEmpresa', auth()->user()->rutEmpresa],
                    ['idSolicitante', $request['idSolicitante']],
                    ['estado', 1],
                ])->get(['id2 as id', 'textoRequerimiento', 'fechaEmail AS Fecha de Email', 'fechaSolicitud AS Fecha de solicitud', 'fechaCierre AS Fecha de cierre', 'fechaRealCierre AS Fecha real de cierre', 'porcentajeEjecutado AS Porcentaje ejecutado', 'nombreSolicitante AS Solicitante', 'nombreResolutor AS Resolutor', 'nameTeam AS Equipo', 'namePriority AS Prioridad', 'textAvance AS Avance'])->toArray();
            }            
        }    

        $base2 = [];
        $requerimientos = [];
        for ($i=0; $i < count($base); $i++) {
            $base[$i]->textoRequerimiento = utf8_decode($base[$i]->textoRequerimiento);
            $base[$i]->Solicitante = utf8_decode($base[$i]->Solicitante);
            $base[$i]->Resolutor = utf8_decode($base[$i]->Resolutor);            
            $base[$i]->Equipo = utf8_decode($base[$i]->Equipo);
            $base[$i]->Avance = utf8_decode($base[$i]->Avance);                        
            $base2 = (array) $base[$i];
            array_push($requerimientos, $base2);
        }          
        if (empty($requerimientos)) {
            return back()->with('msj', 'No existen requerimientos que cumplan con su solicitud.');                
        } else {         
            return view('Extraer.index', compact('requerimientos'));
        }  
    }

    public function resolutors(Request $request)
    {
        if ($request['idResolutor'] != "") {
            $base = DB::table('requ_view')->where([
                ['rutEmpresa', auth()->user()->rutEmpresa],
                ['resolutor', $request['idResolutor']],
                ['estado', 1],
            ])->get(['id2 as id', 'textoRequerimiento', 'fechaEmail AS Fecha de Email', 'fechaSolicitud AS Fecha de solicitud', 'fechaCierre AS Fecha de cierre', 'fechaRealCierre AS Fecha real de cierre', 'porcentajeEjecutado AS Porcentaje ejecutado', 'nombreSolicitante AS Solicitante', 'nombreResolutor AS Resolutor', 'nameTeam AS Equipo', 'namePriority AS Prioridad', 'textAvance AS Avance'])->toArray();
        }
        $base2 = [];
        $requerimientos = [];
        for ($i=0; $i < count($base); $i++) {
            $base[$i]->textoRequerimiento = utf8_decode($base[$i]->textoRequerimiento);
            $base[$i]->Solicitante = utf8_decode($base[$i]->Solicitante);
            $base[$i]->Resolutor = utf8_decode($base[$i]->Resolutor);            
            $base[$i]->Equipo = utf8_decode($base[$i]->Equipo);
            $base[$i]->Avance = utf8_decode($base[$i]->Avance);                        
            $base2 = (array) $base[$i];
            array_push($requerimientos, $base2);
        }                      
        if (empty($requerimientos)) {
            return back()->with('msj', 'No existen requerimientos que cumplan con su solicitud.');                
        } else {         
            return view('Extraer.index', compact('requerimientos'));
        }        
    }

    public function teams(Request $request)
    {
        if ($request['idTeam'] != "") {
            $base = DB::table('requ_view')->where([
                ['rutEmpresa', auth()->user()->rutEmpresa],
                ['teamId', $request['idTeam']],
                ['estado', 1],
            ])->get(['id2 as id', 'textoRequerimiento', 'fechaEmail AS Fecha de Email', 'fechaSolicitud AS Fecha de solicitud', 'fechaCierre AS Fecha de cierre', 'fechaRealCierre AS Fecha real de cierre', 'porcentajeEjecutado AS Porcentaje ejecutado', 'nombreSolicitante AS Solicitante', 'nombreResolutor AS Resolutor', 'nameTeam AS Equipo', 'namePriority AS Prioridad', 'textAvance AS Avance'])->toArray();
        }
        $base2 = [];
        $requerimientos = [];
        for ($i=0; $i < count($base); $i++) {
            $base[$i]->textoRequerimiento = utf8_decode($base[$i]->textoRequerimiento);
            $base[$i]->Solicitante = utf8_decode($base[$i]->Solicitante);
            $base[$i]->Resolutor = utf8_decode($base[$i]->Resolutor);            
            $base[$i]->Equipo = utf8_decode($base[$i]->Equipo);
            $base[$i]->Avance = utf8_decode($base[$i]->Avance);                        
            $base2 = (array) $base[$i];
            array_push($requerimientos, $base2);
        }                      
        if (empty($requerimientos)) {
            return back()->with('msj', 'No existen requerimientos que cumplan con su solicitud.');                
        } else {         
            return view('Extraer.index', compact('requerimientos'));
        }          
    }    
}
