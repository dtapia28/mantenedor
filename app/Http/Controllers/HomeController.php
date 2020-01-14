<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return redirect()->action('RequerimientoController@index');
    }

    static function colorSitio() {
        $param = DB::select('SELECT idColor FROM parametros where rutEmpresa = ?', [Auth::user()->rutEmpresa]);
        $idColor = $param[0]->idColor;      
        return $idColor;
    }

    static function logoEmpresa() {
        $param = DB::select('SELECT linkLogo FROM parametros where rutEmpresa = ?', [Auth::user()->rutEmpresa]);
        $linkLogo = $param[0]->linkLogo;      
        return $linkLogo;
    }

<<<<<<< HEAD
    static function nombreEmpresa(){
        $param = DB::select('SELECT nombreEmpresa FROM empresas where rut = ?', [Auth::user()->rutEmpresa]);
        $nombreEmpresa = $param[0]->nombreEmpresa;
        return $nombreEmpresa;
=======
    static function nombreEmpresa() {
        $sql = DB::select('SELECT nombreEmpresa FROM empresas where rut = ?', [Auth::user()->rutEmpresa]);
        $nomEmpresa = $sql[0]->nombreEmpresa;      
        return $nomEmpresa;
>>>>>>> frontend
    }
}
