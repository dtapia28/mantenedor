<?php

namespace App\Http\Controllers;

use App\Requerimiento;
use App\Avance;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;


class AvanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Requerimiento $requerimiento)
    {
        $avances = Avance::oldest('updated_at')->get();
        dd($avances);
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

        $idRequerimiento = $request->input("idRequerimiento");
        if ($request->input("fechaRealCierre") != null) {
            $requerimiento = DB::table('requerimientos')->select('numeroCambios')->where('id', $request->input("idRequerimiento"))->first();
            $cambios=$requerimiento->numeroCambios;
            $fechaRealCierre = $request->input("fechaRealCierre");
            if ($cambios == null) {
                $cambios = 1;                       
            }                      
        else {
            $cambios +=1;             
            }
        } else {
            $requerimiento = DB::table('requerimientos')->select('fechaRealCierre')->where('id', $request->input("idRequerimiento"))->first();
            $fechaRealCierre = $requerimiento->fechaRealCierre;
            $requerimiento = DB::table('requerimientos')->select('numeroCambios')->where('id', $request->input("idRequerimiento"))->first();
            $cambios=$requerimiento->numeroCambios;                      
        }                               
          
        if ($request->input("porcentajeEjecutado") != null) {
            $porcentaje = $request->input("porcentajeEjecutado"); 
            } else {
                $requerimiento = DB::table('requerimientos')->select('numeroCambios')->where('id', $request->input("idRequerimiento"))->first();
                $porcentaje = $requerimiento->porcentajeEjecutado;               
            }

            $data = [
                'fechaRealCierre' => $fechaRealCierre,
                'porcentajeEjecutado' => $porcentaje,
                'numeroCambios' => $cambios];

        DB::table('requerimientos')->where('id', $request->input('idRequerimiento'))->update($data);            
                                
        return redirect(url("requerimientos/$request->idRequerimiento"));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Avance  $avance
     * @return \Illuminate\Http\Response

    public function show(Avance $avance)
    {
        //
    }
    */
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Avance  $avance
     * @return \Illuminate\Http\Response
     */
    public function edit(Requerimiento $requerimiento, Avance $avance)
    {
        return view('Avances.edit', compact('requerimiento', 'avance'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Avance  $avance
     * @return \Illuminate\Http\Response
     */
    public function update(Requerimiento $requerimiento, Request $request, Avance $avance)
    {
        
        $data = request()->validate([
            'idAvance' => 'required',
            'textAvance' => 'required'
        ]);
        $avance->update($data);
        return redirect(url("requerimientos/$requerimiento->id"));        

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Avance  $avance
     * @return \Illuminate\Http\Response
     */
    public function destroy(Requerimiento $requerimiento, Avance $avance)
    {
        
      $avance->delete();
      return redirect(url("requerimientos/$requerimiento->id"));
    }
}
