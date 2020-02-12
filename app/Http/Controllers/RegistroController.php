<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class RegistroController extends Controller
{
 
    static function crearViaGoogle($nombre)
    {
        $nombre = $nombre;

        return view('auth.register', compact('nombre'));
    }        
}
