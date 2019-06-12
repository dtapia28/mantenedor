<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'WelcomeController@index');

//Rutas para los create

Route::get('/empresas/nueva', 'EmpresaController@create')
	->name('Empresas.create');
	//->middleware('auth');

Route::post('/empresas/crear', 'EmpresaController@store');
	//->middleware('auth');

Route::get('/priorities/nueva', 'PriorityController@create')
	->name('Priority.create')
	->middleware('auth');

Route::post('/priorities/crear', 'PriorityController@store')
	->middleware('auth');

Route::get('/requerimientos/nuevo', 'RequerimientoController@create')
	->name('Requerimiento.create')
	->middleware('auth');

Route::post('/requerimientos/crear', 'RequerimientoController@store')
	->middleware('auth');

Route::get('/resolutors/nuevo', 'ResolutorController@create')
	->name('Resolutor.create')
	->middleware('auth');

Route::post('/resolutors/crear', 'ResolutorController@store')
	->middleware('auth');

Route::get('/solicitantes/nuevo', 'SolicitanteController@create')
	->name('Solicitante.create')
	->middleware('auth');

Route::post('/solicitantes/crear', 'SolicitanteController@store')
	->middleware('auth');

Route::get('/teams/nuevo', 'TeamController@create')
	->name('Team.create')
	->middleware('auth');

Route::post('/teams/crear', 'TeamController@store')
	->middleware('auth');

Route::get('/requerimientos/{requerimiento}/avances/nuevo', 'AvanceController@create')
	->name('Avances.create')
	->middleware('auth');

Route::post('/avances/ingresar', 'AvanceController@store')
	->middleware('auth');




//Rutas para los index
Route::group(['middleware' => 'can:editar'], function(){
	Route::get('/empresas', 'EmpresaController@index');
});	
	//->middleware('auth');

Route::get('/priorities', 'PriorityController@index')
	->middleware('auth');

Route::get('/requerimientos', 'RequerimientoController@index')
	->name('Requerimientos.index')
	->middleware('auth');

Route::get('/resolutors', 'ResolutorController@index')
	->middleware('auth');

Route::get('/solicitantes', 'SolicitanteController@index')
	->middleware('auth');

Route::get('/teams', 'TeamController@index')
	->middleware('auth');

Route::get('/requerimientos/{requerimiento}/avances', 'AvanceController@index')
	->middleware('auth');

//Rutas para los show

Route::get('/empresas/{empresa}', 'EmpresaController@show')
    ->where('id', '[0-9]+')
    ->name('Empresas.show');
	//->middleware('auth');    

Route::get('/priorities/{priority}', 'PriorityController@show')
	->where('id', '[0-9]+')
	->name('Priorities.show')
	->middleware('auth');

Route::get('/requerimientos/{requerimiento}', 'RequerimientoController@show')
	->where('id', '[0-9]+')
	->name('Requerimientos.show')
	->middleware('auth');

Route::get('/resolutors/{resolutor}', 'ResolutorController@show')
	->where('id', '[0-9]+')
	->name('Resolutors.show')
	->middleware('auth');

Route::get('/solicitantes/{solicitante}', 'SolicitanteController@show')
	->where('id', '[0-9]+')
	->name('Solicitantes.show')
	->middleware('auth');

Route::get('/teams/{team}', 'TeamController@show')
	->where('id', '[0-9]+')
	->name('Teams.show')
	->middleware('auth');


Route::get('/requerimientos/{requerimiento}/actualizar', 'RequerimientoController@actualizar')->name('Requerimientos.actualizar')
	->middleware('auth');

Route::put('/requerimientos/{requerimiento}/save', 'RequerimientoController@save')
	->middleware('auth');

//Ruta para requerimiento terminado
Route::get('/requerimientos/{requerimiento}/terminado', 'RequerimientoController@terminado')->name('Requerimientos.terminado')
	->middleware('auth');

Route::put('/requerimientos/{requerimiento}/guardar', 'RequerimientoController@guardar')
	->middleware('auth');

Route::put('/requerimientos/agrupar', 'RequerimientoController@agrupar')
	->middleware('auth');
// Rutas para los edit


Route::get('/empresas/{empresa}/editar', 'EmpresaController@edit')->name('Empresas.edit');
	//->middleware('auth');

Route::put('/empresas/{empresa}', 'EmpresaController@update');
	//->middleware('auth');


Route::get('/priorities/{priority}/editar', 'PriorityController@edit')->name('Priorities.edit')
	->middleware('auth');

Route::put('/priorities/{priority}', 'PriorityController@update')
	->middleware('auth');


Route::get('/requerimientos/{requerimiento}/editar', 'RequerimientoController@edit')->name('Requerimientos.edit')
	->middleware('auth');

Route::put('/requerimientos/{requerimiento}', 'RequerimientoController@update')
	->middleware('auth');


Route::get('/resolutors/{resolutor}/editar', 'ResolutorController@edit')->name('Resolutors.edit')
	->middleware('auth');

Route::put('/resolutors/{resolutor}', 'ResolutorController@update')
	->middleware('auth');


Route::get('/solicitantes/{solicitante}/editar', 'SolicitanteController@edit')->name('Solicitantes.edit')
	->middleware('auth');

Route::put('/solicitantes/{solicitante}', 'SolicitanteController@update')
	->middleware('auth');


Route::get('/teams/{team}/editar', 'TeamController@edit')->name('Teams.edit')
	->middleware('auth');

Route::put('/teams/{team}', 'TeamController@update')
	->middleware('auth');

//Rutas para destroy

Route::delete('/empresas/{empresa}', 'EmpresaController@destroy')->name('Empresas.destroy');
	//->middleware('auth');

Route::delete('/priorities/{priority}', 'PriorityController@destroy')->name('Priorities.destroy')
	->middleware('auth');

Route::delete('/resolutors/{resolutor}', 'ResolutorController@destroy')->name('Resolutor.destroy')
	->middleware('auth');

Route::delete('/teams/{team}', 'TeamController@destroy')->name('Team.destroy')
	->middleware('auth');

Route::delete('/solicitantes/{solicitante}', 'SolicitanteController@destroy')->name('Solicitantes.destroy')
	->middleware('auth');

Route::delete('/requerimientos/{requerimiento}', 'RequerimientoController@destroy')->name('Requerimientos.destroy')
	->middleware('auth');



Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
