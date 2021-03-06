<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MensajesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
<<<<<<< HEAD
        
        $user = auth()->user()->id;
        
        $mensajes = DB::select('select a.id, a.de, (select b.name from users b where a.de=b.id) de_name, para, (select c.name from users c where a.para=c.id) para_name, a.asunto, a.fecha, a.leido from mensajes a where a.rutEmpresa=? and (a.de=? or a.para=?) order by leido asc, fecha desc', [auth()->user()->rutEmpresa, $user, $user]);
        
        $user = DB::table('usuarios')->where('idUser', auth()->user()->id)->get();
        
        return view('Mensajes.index', compact('user', 'mensajes'));
=======
        $user = auth()->user()->id;
        $mensajes = DB::select('select a.id, a.de, (select b.name from users b where a.de=b.id) de_name, para, (select c.name from users c where a.para=c.id) para_name, a.asunto, a.fecha, a.leido from mensajes a where a.rutEmpresa=? and (a.de=? or a.para=?) order by leido asc, fecha desc', [auth()->user()->rutEmpresa, $user, $user]);

        $user = DB::table('usuarios')->where('idUser', auth()->user()->id)->get();

        return view('mensajes.index', compact('user', 'mensajes'));
>>>>>>> 3b4c0926a9818fd4677ab35adddeafed324fe973
    }

    public function nuevo()
    {
        $user = DB::table('usuarios')->where('idUser', auth()->user()->id)->get();
        $users = DB::select('select id, name from users where rutEmpresa = ? and id != ?', [auth()->user()->rutEmpresa, auth()->user()->id]);
        
<<<<<<< HEAD
        return view('Mensajes.create', compact('user', 'users'));
=======
        return view('mensajes.create', compact('user', 'users'));
>>>>>>> 3b4c0926a9818fd4677ab35adddeafed324fe973
    }

    public function store(Request $request)
    {
        $data = request()->validate([
            'nombreDe' => 'required',
            'nombrePara' => 'required|gt:-1',
            'asunto' => 'required',
            'mensaje' => 'required'],
            ['nombreDe.required' => 'El campo De es obligatorio'],
            ['nombrePara.required' => 'El campo Para es obligatorio'],
            ['nombrePara.gt' => 'Debe seleccionar el campo Para de la lista'],
            ['asunto.required' => 'El campo Asunto es obligatorio'],
            ['mensaje.required' => 'El campo Mensaje es obligatorio']);
        
        DB::insert('INSERT INTO mensajes (id, de, para, asunto, mensaje, fecha, leido, rutEmpresa, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [null, $request->nombreDe, $request->nombrePara, $request->asunto, $request->mensaje, date('Y-m-d H:i:s'), 0, auth()->user()->rutEmpresa, now(), now()]);

        return redirect('mensajes')->with('msj', 'El mensaje ha sido enviado correctamente');
    }

    public function show(Request $request)
    {
        $user = auth()->user()->id;
        $mensaje = DB::select("select a.id, a.de, (select b.name from users b where a.de=b.id) de_name, para, (select c.name from users c where a.para=c.id) para_name, a.asunto, a.mensaje, DATE_FORMAT(a.fecha, '%d/%m/%Y %H:%i:%s') fecha, a.leido from mensajes a where a.id = ?", [$request->id]);
        $records = ['respuesta' => true, 'msg' => $mensaje];
        
<<<<<<< HEAD

        if ($mensaje[0]->para == $user)
            DB::update('update mensajes set leido = ? where id = ?', [1, $request->id]);
        

=======
        if ($mensaje[0]->para == $user)
            DB::update('update mensajes set leido = ? where id = ?', [1, $request->id]);
        
>>>>>>> 3b4c0926a9818fd4677ab35adddeafed324fe973
        return response()->json($records, 200);
    }

    public function delete(Request $request)
    {
        DB::delete('delete from mensajes where id = ?', [$request->id]);

        return redirect('mensajes');
    }

    static function mensajesSinLeer() {
        $user = auth()->user()->id;
        $empresa = auth()->user()->rutEmpresa;

        $sql = DB::select('SELECT COUNT(*) sinleer FROM mensajes where rutEmpresa = ? AND para = ? AND leido = ?', [$empresa, $user, 0]);
        $msgSinLeer = $sql[0]->sinleer;

        return $msgSinLeer;
<<<<<<< HEAD
    }    
}
=======
    }
}
>>>>>>> 3b4c0926a9818fd4677ab35adddeafed324fe973
