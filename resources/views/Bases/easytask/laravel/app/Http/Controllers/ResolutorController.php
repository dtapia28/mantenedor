<?php

namespace App\Http\Controllers;

use App\Resolutor;
use App\Empresa;
use App\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\PDO;
use App\Http\Controllers\Config;

class ResolutorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $resolutors = Resolutor::paginate(10);

        return view('Resolutors.index', compact('resolutors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $empresas = Empresa::all();
        $teams = Team::all();

        return view('Resolutors.create', compact('empresas', 'teams'));
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
            'idEmpresa' => 'required'],
            ['nombreResolutor.required' => 'El campo nombre es obligatorio']);


        Resolutor::create([
            'nombreResolutor' => $data['nombreResolutor'],          
            'idEmpresa' => $data['idEmpresa'],
            'idTeam' => $data['idTeam'],
        ]);

        if (Input::get('lider')) {
            $resolutor = DB::table('resolutors')->select('id')->where('nombreResolutor', $data['nombreResolutor'])->first();
            $data = [
                'lider' => 1];
            DB::table('resolutors')->where('id', $resolutor->id)->update($data);
        }
        $resolutor = null;
        return redirect('resolutors');
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
        $empresas = Empresa::all();
        $teams = Team::all();
        return view('Resolutors.edit', compact('empresas', 'teams', 'resolutor')); 
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
            'idEmpresa' => 'required',
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
