<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GraficosLiderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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
        
        return view('dashboard.resolutor_lider', compact($requerimientos, $alDia, $vencer, $vencido));
    }
}
