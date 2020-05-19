<?php

namespace App\Http\Controllers;

use App\Anidado;
use App\Requerimiento;
use App\Resolutor;
use App\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class AnidadoController extends Controller
{    
    /**     
     * Display a listing of the resource.     
     *     
     * @return \Illuminate\Http\Response     
     */    
    
    public function index()    
    {        
        //    
    }    
    /**     
     * Show the form for creating a new resource.     
     *     
     * @return \Illuminate\Http\Response     
     */    
    
    public function create()    
    {        
        //    
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
            'anidar' => 'nullable',            
            'requerimiento' => 'nullable']);        
        
        Anidado::create([            
            'idRequerimientoAnexo' => $data['anidar'],            
            'idRequerimientoBase' => $data['requerimiento'],        
            ]);        
        
        return redirect(url("requerimientos/$request->requerimiento"));    
        
    }    
    
    public function anidar (Requerimiento $requerimiento)    
    {   
        $user = DB::table('usuarios')->where('idUser', auth()->user()->id)->get();        
        $resolutors = Resolutor::where('rutEmpresa', auth()->user()->rutEmpresa)->get();        
        $teams = Team::where('rutEmpresa', auth()->user()->rutEmpresa)->get();        
        $requerimientosAnidadosLista = Anidado::where('idRequerimientoBase', $requerimiento->id)->get();
        $reqs = Requerimiento::where('rutEmpresa', auth()->user()->rutEmpresa)->get();
        $requerimientosAnidados = [];                
        
        foreach ($requerimientosAnidadosLista as $anexo)        
        {                    
            $req = Requerimiento::where('id', $anexo->idRequerimientoAnexo)->first();
            $requerimientosAnidados[]= $req;
        }
        
        $requerimientos = [];
        foreach ($reqs as $req)
        {
            $es_parte = true;
            foreach ($requerimientosAnidadosLista as $anexo)
            {
                if($anexo->idRequerimientoAnexo == $req->id)
                {
                    $es_parte = false;
                }
            }
            
            if($es_parte  == true)
            {
                $requerimientos[] = $req;
            }
        }
        
        return view('Anidar.create', compact('user', 'requerimientosAnidados', 'requerimientos',                    
                    'requerimiento', 'resolutors', 'teams'));    
        
    }    
    
    public function anidara (Request $request)    
    {        
        $idReq = Requerimiento::where('rutEmpresa', auth()->user()->rutEmpresa)->get('id');        
        $nReq = Requerimiento::where('rutEmpresa', auth()->user()->rutEmpresa)->count();        
        $nReq++;        
        
        for ($i=1; $i < $nReq; $i++) {            
            foreach ($idReq as $id)            
            {                
                if ($i = $id->id)                 
                {                    
                    if (Input::get('requerimiento'.$id->id))                     
                    {                        
                        Anidado::create([                            
                            'idRequerimientoAnexo' => $id->id,                            
                            'idRequerimientoBase' => $request->requerimiento,                        
                            ]);                    
                        
                    }                
                }            
            }                  
        }        
        return redirect(url("requerimientos/$request->requerimiento"));            
    }        
    /**     
     * Display the specified resource.     
     *     
     * @param  \App\Anidado  $anidado     
     * @return \Illuminate\Http\Response     
     */    
    
    public function show(Anidado $anidado)    
    {        
        //    
    }    
    /**     
     * Show the form for editing the specified resource.     
     *     
     * @param  \App\Anidado  $anidado     
     * @return \Illuminate\Http\Response     
     */    
    
    public function edit(Anidado $anidado)    
    {        
        //    
    }    
    /**     
     * Update the specified resource in storage.     
     *    
     * @param  \Illuminate\Http\Request  $request     
     * @param  \App\Anidado  $anidado     
     * @return \Illuminate\Http\Response     
     */    
    
    public function update(Request $request, Anidado $anidado)    
    {        
        //    
    }    
    /**     
     * Remove the specified resource from storage.     
     *     
     * @param  \App\Anidado  $anidado     
     * @return \Illuminate\Http\Response     
     */    
    
    public function destroy(Anidado $anidado)    
    {        
        //    
    }
}