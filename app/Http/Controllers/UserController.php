<?php

namespace App\Http\Controllers;
use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use Excel;
use App\Role;
use App\User;
use App\Team;
use App\Role_User;
use App\Resolutor;
use App\Solicitante;
use App\Parametros;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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

    public function cambiarPassword()
    {
        $user = DB::table('usuarios')->where('idUser', auth()->user()->id)->get();
        $lider = 0;
        if ($user[0]->nombre == "resolutor") {
            $resolutor = Resolutor::where('idUser', $user[0]->idUser)->first('lider');
            $lider = $resolutor->lider;           
        }        
        return view('Users.cambiar', compact('user', 'lider'));
    }

    public function change(Request $request)
    {
        $user = User::where('id', $request->usuario)->first();

        $oldPassword = Hash::make($request->oldPassword);

        if (Hash::check($request->oldPassword, $user->password)) {
            if ($request->newPassword == $request->newPassword2) {
                if ($request->oldPassword != $request->newPassword) {
                    $password =Hash::make($request->newPassword);
                    $data = ([
                        'password' => $password]);

                    $user->update($data);

                    return back()->with('msj', 'Contraseña modificada');                    
                }
            } else {
                return back()->with('msj2', 'La confirmación y la nueva contraseña no son iguales');
            }
        } else {
            return back()->with('msj3', 'Contraseña erronea');
        }    
    }

    public function getTeams(){
        $teams = Team::where('rutEmpresa', auth()->user()->rutEmpresa)->get();
        foreach ($teams as $team) {
            $teamArray[$team->id] = $team->nameTeam;
        }

        return response()->json($teamArray);        
    }

    public function getLider(Request $request){
        if ($request->ajax()) {
            $lider = Resolutor::where([
                ['rutEmpresa', auth()->user()->rutEmpresa],
                ['idTeam', $request->id_team],
                ['lider', 1],
            ])->get();
            if (empty($lider)) {
                $liderArray = "No hay nada";

                return $liderArray;
            } else 
            {
                foreach ($lider as $lid) {
                    $liderArray[$lid->id] = $lid->nombreResolutor;
                }
                return response()->json($liderArray);                
            }    
        }        
    }

    public function store(Request $request)
    {
        $data = request()->validate([
            'idRole' => 'required',
            'idTeam' => 'nullable',
        ],
            [ 'idRole.required' => 'El campo nombre es obligatorio']);


        if (isset($data['idTeam'])) {
            $resolutor = Resolutor::where('idUser', $request->idUsers)->first();
            if (isset($resolutor)) {
                $dato = [
                    'idTeam' => $request->idTeam,
                ];
                $resolutor->update($dato);
            } else {
                $role = Role::where('id', $data['idRole'])->first();
                if ($role->nombre == 'resolutor') {
                    $usuario = User::where('id', $request->idUsers)->first();
                    Resolutor::create([
                        'nombreResolutor' => $usuario->name,          
                        'rutEmpresa' => auth()->user()->rutEmpresa,
                        'idTeam' => $data['idTeam'],
                        'idUser' => $usuario->id,
                        'email' => $usuario->email,
                    ]);
                }                
            }           
        }

        $role = Role::where('id', $data['idRole'])->first();
        if ($role->nombre == 'solicitante') {
           $usuario = User::where('id', $request->idUsers)->first(); 
           Solicitante::create([
                'nombreSolicitante' => $usuario->name,
                'rutEmpresa' => auth()->user()->rutEmpresa,
                'idUser' => $usuario->id,
                'email' => $usuario->email,
           ]); 
        }

        $data2 = request()->validate([
        	'idUsers' => 'required',
        ],
    		['idUser.required' => 'El campo es obligatorio']);

        $relacion = Role_User::where('user_id', $data2['idUsers'])->first();
        $relacion->role_id = $data['idRole'];
        $relacion->save();

        return redirect('users');
    }

    public function nuevo(){
        $roles = Role::where('rutEmpresa', auth()->user()->rutEmpresa)->get();
        $user = DB::table('usuarios')->where('idUser', auth()->user()->id)->get();
        auth()->user()->authorizeRoles(['administrador']);

        return view('Users.create', compact('user', 'roles'));                         
    }

    public function guardar (Request $request)
    {

        $data = request()->validate([
            'name' => 'required',
            'apellido' => 'required',
            'n_telefono' => 'nullable',
            'email' => 'required'],    
            ['name.required' => 'El nombre es obligatorio'],
            ['email.required' => 'El mail es obligatorio']);        

            $user = User::create([
            'name' => $data['apellido']." ".$data['name'],
            'rutEmpresa' => auth()->user()->rutEmpresa,
            'email' => $data['email'],
            'password' => Hash::make('secreto'),
            'phone_number' => $data['n_telefono'],    
            'api_token' => Str::random(30),  
            ]);
            
            if (isset($request->idTeam)) {
                if (isset($request->idLider)) {
                    Resolutor::create([
                        'nombreResolutor' => $user->name,          
                        'rutEmpresa' => auth()->user()->rutEmpresa,
                        'idTeam' => $request->idTeam,
                        'idUser' => $user->id,
                        'email' => $user->email,
                        'lider' => 1,
                    ]); 
                } else {
                    Resolutor::create([
                        'nombreResolutor' => $user->name,          
                        'rutEmpresa' => auth()->user()->rutEmpresa,
                        'idTeam' => $request->idTeam,
                        'idUser' => $user->id,
                        'email' => $user->email,
                    ]);                    
                }
                
                $user->roles()->attach(Role::where([
                    ['nombre', 'resolutor'],
                    ['rutEmpresa', auth()->user()->rutEmpresa],
                ])->first());
            } else {
                $role = Role::where('id', $request->idRole)->first();
                if ($role->nombre == 'solicitante') {
                    Solicitante::create([
                        'nombreSolicitante' => $user->name,
                        'rutEmpresa' => auth()->user()->rutEmpresa,
                        'idUser' => $user->id,
                        'email' => $user->email,
                    ]);                       
                }
                $user->roles()->attach(Role::where([
                    ['nombre', $role->nombre],
                    ['rutEmpresa', auth()->user()->rutEmpresa],
                ])->first());
            }         

        if ($request->volver == "1")
            return redirect('requerimientos/nuevo');
        else
            return redirect('users');
    }

    public function cambiar (Request $request)
    {
        $data = request()->validate([
            'cambiar' => 'required',
            'usuario' => 'required'],
            ['cambiar.required' => 'La contraseña es necesaria']);

        $usuario = User::where('id', $data['usuario'])->first();

        $password = Hash::make($data['cambiar']);

        $data2 = ([
            'password' => $password]);

        $usuario->update($data2);

        return back()->with('msj', 'Contraseña de usuario '.$usuario->name.' modificada');

        return redirect('users');
    }

    public function parametros ()
    {
        $user = DB::table('usuarios')->where('idUser', auth()->user()->id)->get();
        $parametros = Parametros::where('rutEmpresa', auth()->user()->rutEmpresa)->first();
        $email = $parametros->emailSupervisor;
        $color = $parametros->idColor;
        return view('Users.parametros', compact('email', 'color', 'user'));
    }
    
    public function miCuenta()
    {
        $user = DB::table('usuarios')->where('idUser', auth()->user()->id)->get();
        $userData = User::where('id', auth()->user()->id)->get(['name', 'email']);

        $nombre = $userData[0]->name;
        $email = $userData[0]->email;
        return view('Users.micuenta', compact('nombre', 'email', 'user'));        
    }    
    
    public function miCuentaGuardar(Request $request)
    {
        $foto = DB::select('select COUNT(*) registros from fotos_perfil where user_id = ?', [auth()->user()->id]);
        $existeFoto = $foto[0]->registros;

        if (!empty($_FILES['archivo']['tmp_name']) || is_uploaded_file($_FILES['archivo']['tmp_name'])) {
            $file = Input::file('archivo'); 
            //Ruta donde queremos guardar las imagenes
            $path = public_path().'/img/fotos_perfil/';
            //archivo con extension
            $filenameWithExt = $request->file('archivo')->getClientOriginalName(); 
            //solo nombre archivo 
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            //solo extension
            $extension = $request->file('archivo')->getClientOriginalExtension();
            //crear nuevo nombre archivo
            $filenameToStore = "foto_".auth()->user()->id.".".$extension;
            \Request::file('archivo')->move($path, $filenameToStore);
        } else {
            session()->flash('message',[
                'alert'=>'warning',
                'text'=>'Debe seleccionar la foto de perfil'
            ]);

            return redirect('user/account');
        }

        if(isset($filenameToStore)) {
            $rutaArchivo = '/img/fotos_perfil/'.$filenameToStore;

            if ($existeFoto > 0) {
                DB::update('update fotos_perfil set linkFoto = ? where user_id = ?', [$rutaArchivo, auth()->user()->id]);
            } else {
                DB::insert('insert into fotos_perfil (id, user_id, linkFoto, created_at, updated_at) values (?, ?, ?, ?, ?)', [null, auth()->user()->id, $rutaArchivo, now(), now()]);
            }
        }

        session()->flash('message',[
            'alert'=>'success',
            'text'=>'Foto de perfil guardada con exito!'
        ]);

        return redirect('user/account');
    }

    public function eliminarFoto(Request $request) {

        DB::delete('delete from fotos_perfil where user_id = ?', [auth()->user()->id]);

        session()->flash('message',[
            'alert'=>'success',
            'text'=>'Foto de perfil eliminada con exito!'
        ]);

        return response()->json(200);
    }    
}
