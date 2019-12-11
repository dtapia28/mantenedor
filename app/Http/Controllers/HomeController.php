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
}
