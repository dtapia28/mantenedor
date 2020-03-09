<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TableroController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request) {
        $user = DB::table('usuarios')->where('idUser', auth()->user()->id)->get(); 
        // Verifica si el usuario logueado es un resolutor marcado como líder
        $resolutor = DB::table('resolutors')->where('idUser', auth()->user()->id)->get();
        $resolutor_lider = isset($resolutor[0]->lider) ? $resolutor[0]->lider : "";
        
        if ($user[0]->nombre == "administrador" || $user[0]->nombre == "supervisor")
            $data = (new GraficosAdministradorController)->index($request);
        elseif($user[0]->nombre == 'resolutor' && $resolutor_lider == 1)
            $data = (new GraficosLiderController)->index($request);
        elseif($user[0]->nombre == 'resolutor' && $resolutor_lider == 0)
            $data = (new GraficosResolutorController)->index($request);
        elseif($user[0]->nombre == 'solicitante')
            $data = (new GraficosSolicitanteController)->index($request);
        
        $valores['requerimientos'] = $data['valores']['requerimientos'];
        $valores['resolutores'] = $data['valores']['resolutores'];
        $valores['solicitantes'] = $data['valores']['solicitantes'];
        $valores['equipos'] = $data['valores']['equipos'];;
        
    	return view('dashboard.index', compact('user', 'resolutor_lider', 'data', 'valores'));
    }
}
