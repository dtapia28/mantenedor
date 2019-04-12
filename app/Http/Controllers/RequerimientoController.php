<?php

namespace App\Http\Controllers;

use App\Requerimiento;
use App\Empresa;
use App\Resolutor;
use App\Priority;
use App\Solicitante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RequerimientoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $requerimientos = Requerimiento::all();

        return view('Requerimientos.index', compact('requerimientos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $empresas = Empresa::all();
        $resolutors = Resolutor::all();
        $priorities = Priority::all();
        $solicitantes = Solicitante::all();

        return view('Requerimientos.create', compact('empresas', 'resolutors', 'priorities', 'solicitantes'));        
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
            'textoRequerimiento' => 'required',
            'fechaEmail' => 'required',
            'fechaSolicitud' => 'required',
            'fechaCierre' => 'required',
            'fechaRealCierre' => 'nullable',
            'numeroCambios' => 'nullable',
            'porcentajeEjecutado' => 'nullable',
            'cierre' => 'nullable',
            'idSolicitante' => 'required',
            'idPrioridad' => 'required',
            'idResolutor' => 'required',
            'idEmpresa' => 'required'],
            ['nombreResolutor.required' => 'El campo nombre es obligatorio'],
            ['fechaEmail.required' => 'La fecha de email es obligatoria'],
            ['fechaSolicitud' => 'La fecha de solicitud es obligatoria'],
            ['fechaCierre' => 'La fecha de cierre es obligatoria']);


        Requerimiento::create([
            'textoRequerimiento' => $data['textoRequerimiento'],            
            'fechaEmail' => $data['fechaEmail'],
            'fechaSolicitud' => $data['fechaSolicitud'],
            'fechaCierre' => $data['fechaCierre'],
            'fechaRealCierre' => $data['fechaRealCierre'],
            'numeroCambios' => $data['numeroCambios'],
            'porcentajeEjecutado' => $data['porcentajeEjecutado'],
            'cierre' => $data['cierre'],
            'idSolicitante' => $data['idSolicitante'],
            'idPrioridad' => $data['idPrioridad'],
            'idResolutor' => $data['idResolutor'],
            'idEmpresa' => $data['idEmpresa'],
        ]);

        return redirect('requerimientos');        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Requerimiento  $requerimiento
     * @return \Illuminate\Http\Response
     */
    public function show(Requerimiento $requerimiento)
    {
        $resolutors = Resolutor::all();
        $priorities = Priority::all();

        return view('Requerimientos.show', compact('requerimiento', 'resolutors', 'priorities'));        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Requerimiento  $requerimiento
     * @return \Illuminate\Http\Response
     */
    public function edit(Requerimiento $requerimiento)
    {
        $solicitantes = Solicitante::all();
        $priorities = Priority::all();
        $resolutors = Resolutor::all();
        $empresas = Empresa::all();

        return view('Requerimientos.edit', compact('requerimiento', 'solicitantes', 'priorities', 'resolutors', 'empresas'));        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Requerimiento  $requerimiento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Requerimiento $requerimiento)
    {
        $data = request()->validate([
            'nombreEmpresa' => 'required',
        ]);
        $empresa->update($data);
        return redirect()->route('Empresas.show', ['empresa' => $empresa]);         
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Requerimiento  $requerimiento
     * @return \Illuminate\Http\Response
     */
    public function destroy(Requerimiento $requerimiento)
    {
        $requerimiento->delete();
        return redirect('requerimientos');   
    }
}
