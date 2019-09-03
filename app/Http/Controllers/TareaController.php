<?php

namespace App\Http\Controllers;

use App\Tarea;
use App\Requerimiento;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use DateTime;

class TareaController extends Controller
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
    public function create(Requerimiento $requerimiento)
    {
        $user = DB::table('usuarios')->where('idUser', auth()->user()->id)->get(); 
        return view('Tareas.create', compact('requerimiento', 'user'));       
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
            'fechaSolicitud' => 'required',
            'fechaCierre' => 'required',
            'texto' => 'required',
            'idRequerimiento' => 'required'],
            ['fechaSolicitud.required' => 'La fecha de solicitud es obligatoria'],
            ['fechaCierre.required' => 'La fecha de cierre es obligatoria'],
            ['texto.required' => 'El texto de la tarea es obligatorio']);            

        $fechaSoli = new DateTime($data['fechaSolicitud']);
        $fechaCie = new DateTime($data['fechaCierre']);
        if ($fechaCie->getTimestamp() >= $fechaSoli->getTimestamp()) 
        {
            $formato = "0";
            $requerimiento = Requerimiento::where('id', $data['idRequerimiento'])->get();
            $tareasReq = Tarea::where('idRequerimiento', $data['idRequerimiento'])->count();
            if ($tareasReq >= 0) {
                $tareasReq++;
            }
            if ($tareasReq < 10) {
                $formato = $formato.$tareasReq;
            }
            Tarea::create([
                'textoTarea' => $data['texto'],
                'fechaSolicitud' => $data['fechaSolicitud'],
                'fechaCierre' => $data['fechaCierre'],
                'idRequerimiento' => $data['idRequerimiento'],
                'id2' => $requerimiento['0']->id2."-".$formato,
            ]);
        }

        return redirect(url("requerimientos/$request->idRequerimiento"));        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Tarea  $tarea
     * @return \Illuminate\Http\Response
     */
    public function show(Tarea $tarea)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Tarea  $tarea
     * @return \Illuminate\Http\Response
     */
    public function edit(Requerimiento $requerimiento, Tarea $tarea)
    {
        $user = DB::table('usuarios')->where('idUser', auth()->user()->id)->get();        
        return view('Tareas.edit', compact('tarea', 'requerimiento', 'user')); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tarea  $tarea
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tarea $tarea)
    {
        //
    }

    public function terminar(Request $request)
    {

        $tarea = Tarea::where('id', $request->tarea)->first();
        $data = [
            'estado' => 2];   
        $tarea->update($data);

        return redirect(url("requerimientos/$request->req"));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tarea  $tarea
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tarea $tarea)
    {
        //
    }
}
