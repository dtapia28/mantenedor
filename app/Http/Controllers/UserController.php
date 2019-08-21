<?php

namespace App\Http\Controllers;
use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use Excel;
use App\Role;
use App\User;
use Illuminate\Support\Facades\DB;

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
            'idRole' => 'required',
            'idUsers' => 'required',
        ],
            [ 'idRole.required' => 'El campo nombre es obligatorio']);

        settype($data['idRole'], "integer");

        $numero = $data['idRole'];

        DB::table('role_user')->where('user_id',$data['idUsers'])
        ->update('role_id', $numero);


        return redirect('users');
    }
}
