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
        $resolutors = Resolutor::all();

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

        if (Input::get('lider')) {
            $lider = 1;
        } else {
            $lider = 0;
        }

        Resolutor::create([
            'nombreResolutor' => $data['nombreResolutor'],
            'lider' => $lider,            
            'idEmpresa' => $data['idEmpresa'],
            'idTeam' => $data['idTeam'],
        ]);

        return redirect()->route('Resolutors.index');
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
        //
    }
}
