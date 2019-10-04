<?php

namespace App\Http\Controllers;

use App\Resolutor;
use App\Empresa;
use App\Team;
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
        return view('Resolutors.show', compact('resolutor'));
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
        return view('Resolutors.edit', compact('teams', 'resolutor', 'user')); 
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
            'nombreResolutor' => 'required',
            'idTeam' => 'required'
        ]);

        $resolutor->update($data);
        return redirect()->route('Resolutors.show', ['resolutor' => $resolutor]);         
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Resolutor  $resolutor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Resolutor $resolutor)
    {
        $resolutor->delete();
        return redirect('resolutors'); 
    }
}
