<?php

namespace App\Http\Controllers;

use App\Resolutor;
use App\Empresa;
use App\Team;
use App\Role;
use App\User;
use App\Requerimiento;
use App\Role_User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class ResolutorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = DB::table('usuarios')->where('idUser', auth()->user()->id)->get();
        $resolutors = Resolutor::where('rutEmpresa', auth()->user()->rutEmpresa)->get();
        $contador = count($resolutors);

        return view('Resolutors.index', compact('resolutors', 'user'));
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
        $teams = Team::where('rutEmpresa', auth()->user()->rutEmpresa)->get();

        return view('Resolutors.create', compact('teams', 'user', 'volver'));
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
            'nombreResolutor' => 'required',
            'idTeam' => 'required',
            'volver' => 'required'],            
            ['nombreResolutor.required' => 'El campo nombre es obligatorio']);


        Resolutor::create([
            'nombreResolutor' => $data['nombreResolutor'],          
            'rutEmpresa' => auth()->user()->rutEmpresa,
            'idTeam' => $data['idTeam'],
            'idUser' => auth()->user()->id,
            'email' => auth()->user()->email,
        ]);

        if (Input::get('lider')) 
        {
            $resolutor = DB::table('resolutors')->select('id')->where('nombreResolutor', $data['nombreResolutor'])->first();
            $data = [
                'lider' => 1];
                DB::table('resolutors')->where('id', $resolutor->id)->update($data);
        }
            $resolutor = null;
        if (isset($_GET['volver'])) 
        {
            if ($data['volver'] == 1) {
                    return redirect()->action('RequerimientoController@create');

            } else {          
                return redirect('resolutors');
            }
        } else {
            return redirect('resolutors');        
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Resolutor  $resolutor
     * @return \Illuminate\Http\Response
     */
    public function show(Resolutor $resolutor)
    {
        $user = DB::table('usuarios')->where('idUser', auth()->user()->id)->get();        
        return view('Resolutors.show', compact('resolutor', 'user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Resolutor  $resolutor
     * @return \Illuminate\Http\Response
     */
    public function edit(Resolutor $resolutor)
    {

        $user = DB::table('usuarios')->where('idUser', auth()->user()->id)->get();        
        $teams = Team::where('rutEmpresa', auth()->user()->rutEmpresa)->get();
        $equipo = Team::where([
            ['rutEmpresa', auth()->user()->rutEmpresa],
            ['id', $resolutor->idTeam],
        ])->first('id');
        $equipo = $equipo->id;
       
        return view('Resolutors.edit', compact('teams', 'resolutor', 'user', 'equipo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Resolutor  $resolutor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Resolutor $resolutor)
    {
        $data = request()->validate([
            'idTeam' => 'required'
        ]);
        $user = DB::table('usuarios')->where('idUser', auth()->user()->id)->get();
        $resolutors = Resolutor::where('rutEmpresa', auth()->user()->rutEmpresa)->get();        

        $resolutor->update($data);
        return view('Resolutors.index', compact('resolutors', 'user'));        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Resolutor  $resolutor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Resolutor $resolutor)
    {
        $req = Requerimiento::where([
            ['rutEmpresa', auth()->user()->rutEmpresa],
            ['resolutor', $resolutor->id]
        ])->first();

        if (isset($req)) {
            return redirect('resolutors')->with('msj', 'No es posible eliminar al resolutor, ya que tiene requerimientos a su cargo');            
        } else {
            $roles = Role::where([
                ['rutEmpresa', auth()->user()->rutEmpresa],
                ['nombre', 'usuario']
            ])->first();

            $usuario = User::where('id', $resolutor->idUser)->first();
            $relacion = Role_User::where('user_id', $usuario->id)->first();
            $relacion->role_id = $roles->id;
            $relacion->save();              

            $resolutor->delete();
            return redirect('resolutors');
        } 
    }
}
