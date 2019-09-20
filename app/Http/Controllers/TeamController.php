<?php

namespace App\Http\Controllers;

use App\Team;
use App\Resolutor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $user = DB::table('usuarios')->where('idUser', auth()->user()->id)->get();
        $teams = Team::where('rutEmpresa', auth()->user()->rutEmpresa)->get();

        return view('Teams.index', compact('teams', 'user'));
    }    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = DB::table('usuarios')->where('idUser', auth()->user()->id)->get();        

        return view('Teams.create', compact('user'));
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
            'nameTeam' => 'required'],
            [ 'nameTeam.required' => 'El campo nombre es obligatorio']);
        //Transforma en array el string nameTeam
        $prueba = str_split($data['nameTeam']);
        $cadena = [];
        $numero = [];
        for ($a=0; $a < count($prueba) ; $a++) { 
            if (is_numeric($prueba[$a])) {
            array_push($numero, $prueba[$a]);
            }
        }

        $numero = implode("", $numero);
       
        //Bucle que agrega al array cadena las 3 primeras letras
        for ($i=0; $i < 3 ; $i++) { 
            $a = strtoupper($prueba[$i]);
            array_push($cadena, $a);
        }

        $nombre = implode("", $cadena);

        $completo = $nombre.$numero;


        Team::create([
            'nameTeam' => $data['nameTeam'],
            'rutEmpresa' => auth()->user()->rutEmpresa,
            'id2' => $completo,
        ]);

        return redirect('teams');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function show(Team $team)
    {

        $user = DB::table('usuarios')->where('idUser', auth()->user()->id)->get();        
        if ($team == null) {
            return view('errors.404');
        }

        return view('Teams.show', compact('team', 'user'));    
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function edit(Team $team)
    {

        $user = DB::table('usuarios')->where('idUser', auth()->user()->id)->get();        
        return view('Teams.edit', compact('team', 'user')); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Team $team)
    {
        $data = request()->validate([
            'nameTeam' => 'required',
        ]);
        $team->update($data);
        return redirect()->route('Teams.show', ['team' => $team]);          
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function destroy(Team $team)
    {
        $team->delete();
        return redirect('teams'); 
    }
}
