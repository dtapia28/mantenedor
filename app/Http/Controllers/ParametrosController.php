<?php

namespace App\Http\Controllers;

use App\Parametros;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input as Input;
use Illuminate\Http\Request;

class ParametrosController extends Controller
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

        auth()->user()->authorizeRoles(['administrador']);

        $data = request()->validate([
            'supervisor' => 'nullable',
            'color' => 'nullable']);
        
        $parametros = Parametros::where('rutEmpresa', auth()->user()->rutEmpresa)->first();
        if(isset($parametros))
        {
            if (!empty($_FILES['archivo']['tmp_name']) || is_uploaded_file($_FILES['archivo']['tmp_name'])){
                $file = Input::file('archivo'); 
                //Ruta donde queremos guardar las imagenes
                $path = public_path().'/img/logos/';
                //archivo con extension
                $filenameWithExt = $request->file('archivo')->getClientOriginalName(); 
                //solo nombre archivo 
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                //solo extension
                $extension = $request->file('archivo')->getClientOriginalExtension();
                //crear nuevo nombre archivo
                $rutEmpresa = explode("-", auth()->user()->rutEmpresa);
                $filenameToStore = "logo_".$rutEmpresa[0].".".$extension;
                \Request::file('archivo')->move($path, $filenameToStore);
            }

            if(isset($filenameToStore)) {
                Parametros::where('id', $parametros->id)
                ->update([
                    'emailSupervisor'=> $data['supervisor'],
                    'idColor' => $data['color'],
                    'linkLogo' => '/img/logos/'.$filenameToStore,
                ]);
            } else {
                Parametros::where('id', $parametros->id)
                ->update([
                    'emailSupervisor'=> $data['supervisor'],
                    'idColor' => $data['color'],
                ]);
            }
        } else
        {   
            Parametros::create([
                'rutEmpresa' => auth()->user()->rutEmpresa,
                'emailSupervisor' => $data['supervisor'],
                'idColor' => $data['color']]);
        }

        session()->flash('message',[
            'alert'=>'success',
            'text'=>'Parámetros guardados con exito!'
        ]);

        return redirect('user/parametros');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Parametros  $parametros
     * @return \Illuminate\Http\Response
     */
    public function show(Parametros $parametros)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Parametros  $parametros
     * @return \Illuminate\Http\Response
     */
    public function edit(Parametros $parametros)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Parametros  $parametros
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Parametros $parametros)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Parametros  $parametros
     * @return \Illuminate\Http\Response
     */
    public function destroy(Parametros $parametros)
    {
        //
    }
}
