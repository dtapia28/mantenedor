<?php

namespace App\Http\Controllers;

use App\Solicitante;
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
        $solicitantes = Solicitante::all();

        return view('Solicitantes.index', compact('solicitantes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Solicitantes.create');
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
            'nombreSolicitante' => 'required'],
            [ 'nombreSolicitante.required' => 'El campo nombre es obligatorio']);

        Solicitante::create([
            'nombreSolicitante' => $data['nombreSolicitante']]);

        return redirect('solicitantes');        
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

        return view('Solicitantes.show', compact('solicitante'));
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
        $solicitante->delete();
        return redirect('solicitantes');   
    }
}
