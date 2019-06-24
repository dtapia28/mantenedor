<?php

namespace App\Http\Controllers;

use App\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $teams = Team::where('rutEmpresa', auth()->user()->rutEmpresa)->get();

        return view('Teams.index', compact('teams'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Teams.create');
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
            'nameTeam' => 'required'],
            [ 'nameTeam.required' => 'El campo nombre es obligatorio']);

        Team::create([
            'nameTeam' => $data['nameTeam'],
            'rutEmpresa' => auth()->user()->rutEmpresa,
        ]);

        return redirect('teams');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function show(Team $team)
    {
        if ($team == null) {
            return view('errors.404');
        }

        return view('Teams.show', compact('team'));    
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function edit(Team $team)
    {
        return view('Teams.edit', ['team' => $team]); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Team $team)
    {
        $data = request()->validate([
            'nameTeam' => 'required',
        ]);
        $team->update($data);
        return redirect()->route('Teams.show', ['team' => $team]);          
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function destroy(Team $team)
    {
        $team->delete();
        return redirect('teams'); 
    }
}
