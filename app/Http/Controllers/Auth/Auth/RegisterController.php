<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Empresa;
use App\Role;
use App\Parametros;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/requerimientos';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'rut' => ['required', 'string', 'max:10'],
            'nombre' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $empresa = Empresa::where('rut', $data['rut'])->get();
        if ($empresa->count() != 0) {

        $user = User::create([
            'name' => $data['name'],
            'rutEmpresa' => $data['rut'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $user->roles()->attach(Role::where([
            ['nombre', 'usuario'],
            ['rutEmpresa', $data['rut']],
        ])->first());

        } else {

        Empresa::create([
            'rut' => $data['rut'],
            'nombreEmpresa' => $data['nombre']]);

        Role::create([
            'nombre' => "administrador",
            'descripcion' => "Administrador",
            'rutEmpresa' => $data['rut'],            
        ]);

        Role::create([
            'nombre' => "usuario",
            'descripcion' => "Usuario",
            'rutEmpresa' => $data['rut'],            
        ]);

        Role::create([
            'nombre' => "supervisor",
            'descripcion' => "Supervisor",
            'rutEmpresa' => $data['rut'],            
        ]);

        Role::create([
            'nombre' => "resolutor",
            'descripcion' => "Resolutor",
            'rutEmpresa' => $data['rut'],            
        ]); 

        Role::create([
            'nombre' => "solicitante",
            'descripcion' => "Solicitante",
            'rutEmpresa' => $data['rut'],            
        ]);

        Parametros::create([
            'rutEmpresa' => $data['rut'],
            'emailSupervisor' => $data['email'],
            'idColor' => 1,
        ]);                                       

        $user = User::create([
            'name' => $data['name'],
            'rutEmpresa' => $data['rut'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $user->roles()->attach(Role::where([
            ['nombre', 'administrador'],
            ['rutEmpresa', $data['rut']],
        ])->first());

        }

        return $user;
    }
}
