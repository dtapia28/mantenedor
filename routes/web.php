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

Route::get('/', function () {
    return view('welcome');
});

//Rutas para los create

Route::get('/empresas/nueva', 'EmpresaController@create')
	->name('Empresas.create');

Route::post('/empresas/crear', 'EmpresaController@store');

Route::get('/priorities/nueva', 'PriorityController@create')
	->name('Priority.create');

Route::post('/priorities/crear', 'PriorityController@store');

Route::get('/requerimientos/nuevo', 'RequerimientoController@create')
	->name('Requerimiento.create');

Route::post('/requerimientos/crear', 'RequerimientoController@store');

Route::get('/resolutors/nuevo', 'ResolutorController@create')
	->name('Resolutor.create');

Route::post('/resolutors/crear', 'ResolutorController@store');

Route::get('/solicitantes/nuevo', 'SolicitanteController@create')
	->name('Solicitante.create');

Route::post('/solicitantes/crear', 'SolicitanteController@store');

Route::get('/teams/nuevo', 'TeamController@create')
	->name('Team.create');

Route::post('/teams/crear', 'TeamController@store');

//Rutas para los index
Route::get('/empresas', 'EmpresaController@index');

Route::get('/priorities', 'PriorityController@index');

Route::get('/requerimientos', 'RequerimientoController@index');

Route::get('/resolutors', 'ResolutorController@index');

Route::get('/solicitantes', 'SolicitanteController@index');

Route::get('/teams', 'TeamController@index');

//Rutas para los show

Route::get('/empresas/{empresa}', 'EmpresaController@show')
    ->where('id', '[0-9]+')
    ->name('Empresas.show');    

Route::get('/priorities/{priority}', 'PriorityController@show')
	->where('id', '[0-9]+')
	->name('Priorities.show');

Route::get('/requerimientos/{requerimiento}', 'RequerimientoController@show')
	->where('id', '[0-9]+')
	->name('Requerimientos.show');

Route::get('/resolutors/{resolutor}', 'ResolutorController@show')
	->where('id', '[0-9]+')
	->name('Resolutors.show');

Route::get('/solicitantes/{solicitante}', 'SolicitanteController@show')
	->where('id', '[0-9]+')
	->name('Solicitantes.show');

Route::get('/teams/{team}', 'TeamController@show')
	->where('id', '[0-9]+')
	->name('Teams.show');

// Rutas para los edit


Route::get('/empresas/{empresa}/editar', 'EmpresaController@edit')->name('Empresas.edit');

Route::put('/empresas/{empresa}', 'EmpresaController@update');


Route::get('/priorities/{priority}/editar', 'PriorityController@edit')->name('Priorities.edit');

Route::put('/priorities/{priority}', 'PriorityController@update');


Route::get('/requerimientos/{requerimiento}/editar', 'RequerimientoController@edit')->name('Requerimientos.edit');

Route::put('/requerimientos/{requerimiento}', 'RequerimientoController@update');


Route::get('/resolutors/{resolutor}/editar', 'ResolutorController@edit')->name('Resolutors.edit');

Route::put('/resolutors/{resolutor}', 'ResolutorController@update');


Route::get('/solicitantes/{solicitante}/editar', 'SolicitanteController@edit')->name('Solicitantes.edit');

Route::put('/solicitantes/{solicitante}', 'SolicitanteController@update');


Route::get('/teams/{team}/editar', 'TeamController@edit')->name('Teams.edit');

Route::put('/teams/{team}', 'TeamController@update');

//Rutas para destroy

Route::delete('/empresas/{empresa}', 'EmpresaController@destroy')->name('Empresas.destroy');

Route::delete('/priorities/{priority}', 'PriorityController@destroy')->name('Priorities.destroy');

Route::delete('/resolutors/{resolutor}', 'ResolutorController@destroy')->name('Resolutor.destroy');

Route::delete('/teams/{team}', 'TeamController@destroy')->name('Team.destroy');

Route::delete('/solicitantes/{solicitante}', 'SolicitanteController@destroy')->name('Solicitantes.destroy');

Route::delete('/requerimientos/{requerimiento}', 'RequerimientoController@destroy')->name('Requerimientos.destroy');