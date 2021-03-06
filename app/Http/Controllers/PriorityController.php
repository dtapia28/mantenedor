<?php

namespace App\Http\Controllers;

use App\Priority;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PriorityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = DB::table('usuarios')->where('idUser', auth()->user()->id)->get();

        $priorities = Priority::where('rutEmpresa', auth()->user()->rutEmpresa)->get();

        return view('Priorities.index', compact('priorities', 'user'));
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
        return view('Priorities.create', compact('user', 'volver'));
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
            'namePriority' => 'required',
            'volver' => 'required'],            
            [ 'namePriority.required' => 'El campo nombre es obligatorio']);

        Priority::create([
            'namePriority' => $data['namePriority'],
            'rutEmpresa' => auth()->user()->rutEmpresa,
        ]);

        if ($data['volver'] == 1) {
            return redirect()->action('RequerimientoController@create');
        } else {
            return redirect('priorities');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Priority  $priority
     * @return \Illuminate\Http\Response
     */
    public function show(Priority $priority)
    {
        $user = DB::table('usuarios')->where('idUser', auth()->user()->id)->get();        
        return view('Priorities.show', compact('priority', 'user'));        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Priority  $priority
     * @return \Illuminate\Http\Response
     */
    public function edit(Priority $priority)
    {
        $user = DB::table('usuarios')->where('idUser', auth()->user()->id)->get();
        return view('Priorities.edit', compact('priority', 'user')); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Priority  $priority
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Priority $priority)
    {
       $data = request()->validate([
            'namePriority' => 'required',
        ]);
        $priority->update($data);
        return redirect()->route('Priorities.show', ['priority' => $priority]);        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Priority  $priority
     * @return \Illuminate\Http\Response
     */
    public function destroy(Priority $priority)
    {
        $priority->delete();
        return redirect('priorities'); 
    }
}
