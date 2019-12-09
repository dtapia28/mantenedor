<?php

namespace App\Http\Controllers;

use App\Solicitante;
use App\Role;
use App\User;
use App\Role_User;
use App\Requerimiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SolicitanteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = DB::table('usuarios')->where('idUser', auth()->user()->id)->get();

        $solicitantes = Solicitante::where('rutEmpresa', auth()->user()->rutEmpresa)->get();

        return view('Solicitantes.index', compact('solicitantes', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $volver = 0;
        if (isset($_GET['volver'])) {
            $volver = $_GET['volver'];
        }

        $user = DB::table('usuarios')->where('idUser', auth()->user()->id)->get();     
        return view('Solicitantes.create', compact('user', 'volver'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = request()->validate([
            'nombreSolicitante' => 'required',
            'volver' => 'required'],
            [ 'nombreSolicitante.required' => 'El campo nombre es obligatorio']);

        Solicitante::create([
            'nombreSolicitante' => $data['nombreSolicitante'],
            'rutEmpresa' => auth()->user()->rutEmpresa,
            'idUser' => auth()->user()->id,
            'email' => auth()->user()->email,
        ]);

        if ($data['volver'] == 1) {
            return redirect()->action('RequerimientoController@create');

        } else {

        return redirect('solicitantes'); 
        }       
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Solicitante  $solicitante
     * @return \Illuminate\Http\Response
     */
    public function show(Solicitante $solicitante)
    {
        if ($solicitante == null) {
            return view('errors.404');
        }
        $user = DB::table('usuarios')->where('idUser', auth()->user()->id)->get();  

        return view('Solicitantes.show', compact('solicitante', 'user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Solicitante  $solicitante
     * @return \Illuminate\Http\Response
     */
    public function edit(Solicitante $solicitante)
    {
        return view('Solicitantes.edit', ['solicitante' => $solicitante]);        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Solicitante  $solicitante
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Solicitante $solicitante)
    {
        $data = request()->validate([
            'nombreSolicitante' => 'required',
        ]);
        $solicitante->update($data);
        return redirect()->route('Solicitantes.show', ['solicitante' => $solicitante]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Solicitante  $solicitante
     * @return \Illuminate\Http\Response
     */
    public function destroy(Solicitante $solicitante)
    {
        $req = Requerimiento::where([
            ['rutEmpresa', auth()->user()->rutEmpresa],
            ['idSolicitante', $solicitante->id]
        ])->first();

        if (isset($req)) {
            return redirect('solicitantes')->with('msj', 'No es posible eliminar al solicitante, ya que tiene requerimientos solicitados');
        } else {    
            $roles = Role::where([
                ['rutEmpresa', auth()->user()->rutEmpresa],
                ['nombre', 'usuario']
            ])->first();

            $usuario = User::where('id', $solicitante->idUser)->first();
            $relacion = Role_User::where('user_id', $usuario->id)->first();
            $relacion->role_id = $roles->id;
            $relacion->save();         
            $solicitante->delete();
            return redirect('solicitantes');
        }   
    }
}
