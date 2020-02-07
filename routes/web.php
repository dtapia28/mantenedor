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



//Route::get('/', 'WelcomeController@index');
Route::get('/', function () {
    return redirect('/login');
});

Route::get('/home', 'RequerimientoController@index')

	->name('Requerimientos.index')

	->middleware('auth');


Route::get('/download', 'UserController@exportar');

Route::get('/downloadReq', 'RequerimientoController@exportar');



Route::get('/extracciones', 'ExtraerController@index')
		->middleware('auth');
Route::get('extracciones/ver', 'ExtraerController@porEstado')
		->middleware('auth');

Route::get('extracciones/word', 'ExtraerController@word')
		->middleware('auth');		

Route::get('/extracciones/estado', 'ExtraerController@porEstado')
		->middleware('auth');

Route::get('/extracciones/ejecutado', 'ExtraerController@porEjecutado')
		->middleware('auth');

Route::get('/extracciones/cambios', 'ExtraerController@cambios')
		->middleware('auth');

Route::get('/extracciones/solicitantes', 'ExtraerController@solicitantes')
		->middleware('auth');

Route::get('/extracciones/resolutores', 'ExtraerController@resolutors')
		->middleware('auth');

Route::get('/extracciones/teams', 'ExtraerController@teams')
		->middleware('auth');



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

Route::get('/requerimientos/script', 'RequerimientoController@getResolutors');
Route::get('/users/script', 'UserController@getTeams');
Route::get('/requerimientos/script2', 'UserController@getTeams');
Route::get('/users/script3', 'UserController@getLider');


Route::get('/requerimientos/{requerimiento}/avances/{avance}/editar', 'AvanceController@edit')->name('Avances.edit');	



Route::post('/avances/ingresar', 'AvanceController@store')

	->middleware('auth');



Route::put('/requerimientos/{requerimiento}/avances/{avance}', 'AvanceController@update')
	->middleware('auth');
Route::post('/requerimientos/{requerimiento}/activar', 'RequerimientoController@activar')
	->middleware('auth');

Route::post('/requerimientos/{requerimiento}/autorizar', 'RequerimientoController@autorizar')
	->middleware('auth');		









//Rutas para los index

//Route::group(['middleware' => 'can:ver'], function()

//{


	Route::get('/dashboard', 'DashboardController@index')
		->middleware('auth');

	Route::get('/dashboard/green', 'DashboardController@green')
	->middleware('auth');

	Route::get('/dashboard/yellow', 'DashboardController@yellow')
		->middleware('auth');

	Route::get('/dashboard/red', 'DashboardController@red')
		->middleware('auth');;		

	

//});	

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

Route::get('/users', 'UserController@index')
	->middleware('auth');

Route::get('/user/parametros', 'UserController@parametros')
	->middleware('auth');

Route::post('/user/parametros/guardar', 'ParametrosController@store')
	->middleware('auth');		

Route::get('/users/nuevo', 'UserController@nuevo')
	->middleware('auth');


Route::post('/users/guardar', 'UserController@guardar')
	->middleware('auth');	

Route::post('/users/modificar', 'UserController@store')
	->middleware('auth');

Route::post('/users/cambiar', 'UserController@cambiar')
	->middleware('auth');

Route::get('/user/changepassword', 'UserController@cambiarPassword')
	->middleware('auth');

Route::post('/user/change', 'UserController@change')
	->middleware('auth');					



Route::get('/requerimientos/{requerimiento}/avances', 'AvanceController@index')

	->middleware('auth');

Route::any('prueba/', 'RequerimientoController@prueba');


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



Route::delete('/requerimientos/{requerimiento}/avances/{avance}', 'AvanceController@destroy')->name('Avances.destroy')
	->middleware('auth');



Auth::routes();

//Route::post('/requerimientos/anidar', 'AnidadoController@store');

Route::get('/requerimientos/{requerimiento}/anidar', 'AnidadoController@anidar');
Route::post('/requerimientos/{requerimiento}/anidar', 'AnidadoController@anidara');
Route::get('/home', 'HomeController@index')->name('home');

Route::get('/requerimientos/{requerimiento}/tareas/nueva', 'TareaController@create')

	->name('Tareas.create')

	->middleware('auth');

Route::post('/tareas/ingresar', 'TareaController@store')

	->middleware('auth');


Route::get('/requerimientos/{requerimiento}/tareas/{tarea}/terminar', 'TareaController@terminar');

Route::get('/requerimientos/{requerimiento}/tareas/{tarea}/editar', 'TareaController@edit');

Route::put('/requerimientos/{requerimiento}/tareas/{tarea}', 'TareaController@update');

Route::delete('/requerimientos/{requerimiento}/tareas/{tarea}/eliminar', 'TareaController@destroy');

/* NUEVAS RUTAS - César Ramos */
Route::get('/requerimientos/{requerimiento}/aceptar', 'RequerimientoController@aceptar');
Route::get('/requerimientos/{requerimiento}/rechazar', 'RequerimientoController@rechazar');
Route::post('/requerimientos/{requerimiento}/rechazar', 'RequerimientoController@RequerimientoRechazado');
/* ************************** */

Route::get('/clear_cache', function()
{
	Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    Artisan::call('config:cache');
    Artisan::call('config:clear');
    Artisan::call('optimize:clear');
    return "Caché limpiado";
});


/* Rutas para pruebascontroladores en Tablero - Daniel Tapia */
Route::get('/dashboard/prueba','GraficosSolicitanteController@index');
