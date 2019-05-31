<?php

namespace App\Http\Controllers;

use App\Empresa;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index()
    {
    	$empresas = Empresa::all();

    	return view('welcome', compact('empresas'));
    }
}
