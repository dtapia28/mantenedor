<?php

namespace App\Http\Controllers;

use App\Requerimiento;
use App\Avance;
use Illuminate\Http\Request;
use Carbon\Carbon;


class AvanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Requerimiento $requerimiento)
    {
        $avances = Avance::all();
        return view('Avances.index', compact('requerimiento', 'avances'));         
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Requerimiento $requerimiento)
    {


        return view('Avances.create', compact('requerimiento'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $contador = 0;
        $avances = Avance::all();
        if (condition) {
            # code...
        }
        $data = request()->validate([
            'textAvance' => 'required',
            'idRequerimiento' => 'required'],
            ['textAvance.required' => 'El campo texto del avance es obligatorio']);
        $id = request()->validate([
            'idRequerimiento' => 'required']);

        Avance::create([
            'textAvance' => $data['textAvance'],
            'fechaAvance' => Carbon::now(),
            'idRequerimiento' => $data['idRequerimiento']
        ]);

        return redirect('requerimientos/');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Avance  $avance
     * @return \Illuminate\Http\Response
     */
    public function show(Avance $avance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Avance  $avance
     * @return \Illuminate\Http\Response
     */
    public function edit(Avance $avance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Avance  $avance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Avance $avance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Avance  $avance
     * @return \Illuminate\Http\Response
     */
    public function destroy(Avance $avance)
    {
        //
    }
}
