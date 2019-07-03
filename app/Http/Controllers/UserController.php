<?php

namespace App\Http\Controllers;
use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use Excel;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function exportar()
    {
    	return Excel::download(new UsersExport, 'usuarios.xlsx');
    }
}
