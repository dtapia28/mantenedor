<?php

namespace App\Http\Controllers;
use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use Excel;
use App\Role;
use App\User;
use App\Role_User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
    	$roles = Role::where('rutEmpresa', auth()->user()->rutEmpresa)->get();
        $user = DB::table('usuarios')->where('idUser', auth()->user()->id)->get();
        $users = User::where('rutEmpresa', auth()->user()->rutEmpresa)->get();
        $relaciones = DB::table('role_user')->get();

        //$request->user()->authorizeRoles(['usuario', 'administrador', 'supervisor', 'resolutor']);


        return view('Users.index', compact('users', 'user', 'roles', 'relaciones'));

    }	
    public function store(Request $request)
    {

        $data = request()->validate([
            'role_id' => 'required',
        ],
            [ 'idRole.required' => 'El campo nombre es obligatorio']);

        $data2 = request()->validate([
        	'user_id' => 'required',
        ],
    		['idUser.required' => 'El campo es obligatorio']);

        $relacion = Role_User::where('user_id', $data2['user_id'])->first();
        $relacion->role_id = $data['role_id'];
        $relacion->save();

        return redirect('users');
    }

    public function nuevo(){
        $user = DB::table('usuarios')->where('idUser', auth()->user()->id)->get();
        auth()->user()->authorizeRoles(['administrador']);

        return view('Users.create', compact('user'));                         
    }

    public function guardar (Request $request)
    {

        $data = request()->validate([
            'name' => 'required',
            'email' => 'required'],
            ['name.required' => 'El nombre es obligatorio'],
            ['email.required' => 'El mail es obligatorio']);        

            $user = User::create([
            'name' => $data['name'],
            'rutEmpresa' => auth()->user()->rutEmpresa,
            'email' => $data['email'],
            'password' => Hash::make('secreto'),
            ]);

        $user->roles()->attach(Role::where('nombre', 'usuario')->first());            

            return redirect('users');            
    }
}
